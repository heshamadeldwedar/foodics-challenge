<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

class CheckStockAndNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $order;

    public function handle()
    {
        $ingredients = Ingredient::where('current_stock', '<', DB::raw('max_stock / 2'))
            ->where('alert_sent', 0)
            ->whereNotNull('supplier_email')
            ->get();

        if ($ingredients->count() > 0) {
            $ingredients->each(function ($ingredient) {

                $ingredient->update(['alert_sent' => 1]);

                Notification::route('mail', $ingredient->supplier_email)
                    ->notify(new LowStockNotification($ingredient));

            });

        }
    }
}
