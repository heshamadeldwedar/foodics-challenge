<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Jobs\CheckStockAndNotify;
use App\Jobs\UpdateProductStock;
use App\Models\Order;
use Illuminate\Support\Facades\Bus;

class OrderService
{
    public function index()
    {
        return Order::with('products')->get();
    }

    public function store(CreateOrderRequest $data)
    {
        $order = $this->createOrder($data->orders);

        Bus::chain([
            new UpdateProductStock($order),
            new CheckStockAndNotify(),
        ])->dispatch();


        return $order;

    }

    protected function createOrder($orders)
    {
        $result = Order::create();
        foreach ($orders as $order) {
            $result->products()->attach($order['product_id'], ['quantity' => $order['quantity']]);
        }
        return $result;
    }
}
