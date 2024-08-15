<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Exception;
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

        UpdateProductStock::dispatch($data->orders);

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

    protected function validateHaveEnoughStock($orders)
    {
        $productIds = collect($orders)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with(['ingredients.stockUnit'])->get();
        foreach ($products as $product) {
            $quantity = collect($orders)->pluck('quantity')->first();
            if (!$product->haveEnoughStock($quantity)) {
                throw new Exception('Not enough stock');
            }
        }
    }
}
