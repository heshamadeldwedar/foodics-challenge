<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;
use Mockery;
use Illuminate\Support\Facades\Queue;
use App\Jobs\CheckStockAndNotify;
use App\Jobs\UpdateProductStock;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    public $seed = true;

    public function test_get_empty_order(): void
    {
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
        $response->assertJSON([]);
    }

    public function test_create_order(): void
    {
        $response = $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response->assertStatus(201);
        $response->assertJSON([
            'id' => 1,
        ]);
    }

    public function test_get_one_order(): void
    {
        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'updated_at',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'pivot' => [
                            'order_id',
                            'product_id',
                            'quantity',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);
    }

    public function test_create_order_with_2_quantity(): void
    {
        $response = $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                ],
            ],
        ]);
        $response->assertStatus(201);
        $response->assertJSON([
            'id' => 1,
        ]);
    }

    public function test_get_two_orders(): void
    {
        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);
        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                ],
            ],
        ]);
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $response->assertJsonCount(2);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'updated_at',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'pivot' => [
                            'order_id',
                            'product_id',
                            'quantity',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);
        $response->assertJsonFragment([
            'id' => 2,
            'product_id' => 1,
            'quantity' => 2,
        ]);
    }

    public function test_create_order_with_not_enough_stock(): void
    {

        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 51,
                ],
            ],
        ]);

        // Order should be cancelled
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => 'cancelled',
            'cancellation_reason' => 'Not enough stock',
        ]);

        // Stock Is not updated //
        $this->assertDatabaseHas(('ingredients'), [
            'name' => 'Beef',
            'current_stock' => 20000,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'current_stock' => 1000,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'current_stock' => 5000,
        ]);

    }

    public function test_stock_change(): void
    {
        // before removing 150 gm of beef, 30 gm of cheese, 20 gm of onion
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Beef',
            'current_stock' => 20000,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'current_stock' => 1000,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'current_stock' => 5000,
        ]);

        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ]);

        // after removing 150 gm of beef, 30 gm of cheese, 20 gm of onion //
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Beef',
            'current_stock' => 19850,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'current_stock' => 980,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'current_stock' => 4970,
        ]);
    }

    public function test_email_not_sent_after_half_ingredients_consumed(): void
    {
        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 25,
                ],
            ],
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'alert_sent' => 0,
            'current_stock' => 500,
            'max_stock' => 1000,
        ]);
    }

    public function test_email_sent_after_more_than_half_ingredients_consumed(): void
    {
        $this->post('/api/orders', [
            'orders' => [
                [
                    'product_id' => 1,
                    'quantity' => 26,
                ],
            ],
        ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'alert_sent' => 1,
            'current_stock' => 480,
            'max_stock' => 1000,
        ]);
    }
}
