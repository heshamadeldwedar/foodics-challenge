<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function index()
    {
        return Order::with('products')->get();
    }

    public function store(CreateOrderRequest $data)
    {

        return DB::transaction(function () use ($data) {
            try {

                $this->validateHaveEnoughStock($data->orders);
                $order = $this->createOrder($data->orders);
                $this->updateStock($data->orders);
                DB::commit();

                return $order;
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        });

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

    protected function updateStock($orders)
    {
        $productIds = collect($orders)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with(['ingredients.stockUnit'])->get();
        foreach ($products as $product) {
            $quantity = collect($orders)->pluck('quantity')->first();
            $product->updateStock($quantity);
        }
    }
}
