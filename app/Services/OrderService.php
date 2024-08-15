<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Bus;
use App\Jobs\UpdateProductStock;

class OrderService
{
    public function index()
    {
        return Order::with('products')->get();
    }

    public function store(CreateOrderRequest $data)
    {
        $order = $this->createOrder($data->orders);

        UpdateProductStock::dispatch($order);


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
