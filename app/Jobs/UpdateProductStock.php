<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class UpdateProductStock implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    private $orders = [];

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function handle(): void
    {
        $productIds = collect($this->orders)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with(['ingredients.stockUnit'])->get();
        foreach ($products as $product) {
            $quantity = collect($this->orders)->pluck('quantity')->first();
            $product->updateStock($quantity);
        }
    }
}
