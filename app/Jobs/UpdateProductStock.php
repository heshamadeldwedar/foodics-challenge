<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;


class UpdateProductStock implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 1;

    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        $orders = DB::table('order_product')->where('order_id', $this->order->id)->get();
        $productIds = collect($orders)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->with(['ingredients.stockUnit'])->get();
        foreach ($products as $product) {
            $quantity = collect($orders)->pluck('quantity')->first();
            $product->updateStock($quantity);
        }
    }

    public function failed(Exception $exception): void
    {
        Order::where('id', $this->order->id)->lockForUpdate()->update(['status' => Order::STATUS_FAILED, 'cancellation_reason' => 'Not enough stock']);
    }
}
