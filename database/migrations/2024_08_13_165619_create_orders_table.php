<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('cancellation_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
