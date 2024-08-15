<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('current_stock', 10, 3);
            $table->unsignedBigInteger('stock_unit_id');
            $table->decimal('max_stock', 10, 3);
            $table->boolean('alert_sent')->default(false);
            $table->string('supplier_email')->nullable();
            $table->timestamps();
            $table->foreign('stock_unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
