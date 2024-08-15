<?php

namespace App\Services;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Product;

class OrderService
{

    public function index() {
        return Order::with('products')->get();
    }

    public function store(CreateOrderRequest $data) {

        return DB::transaction(function () use ($data) {
            try {
                $this->validateHaveEnoughStock($data->orders);
                $order = $this->createOrder($data->orders);
                DB::commit();
                return $order;
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });

    }

    protected function createOrder($orders) {
        $result = Order::create();
        foreach ($orders as $order) {
            $result->products()->attach($order['product_id'], ['quantity' => $order['quantity']]);
        }
        return $result;
    }

    protected function validateHaveEnoughStock($orders) {
        $productIds = collect($orders)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with('ingredients')->get();
        foreach ($products as $product) {
            if (!$product->haveEnoughStock(collect($orders)->pluck('quantity')->first())) {
                throw new Exception('Not enough stock');
            }
        }
    }


}
