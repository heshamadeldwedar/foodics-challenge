<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

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
            'id' => 2,
        ]);
    }

    public function test_get_two_orders(): void
    {
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
}
