<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_stock',
        'max_stock',
        'stock_unit_id',
        'alert_sent',
    ];

    protected $casts = [
        'current_stock' => 'decimal:3',
        'max_stock' => 'decimal:3',
        'alert_sent' => 'boolean',
    ];

    public function stockUnit()
    {
        return $this->belongsTo(Unit::class, 'stock_unit_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot('amount', 'unit_id')
            ->withTimestamps();
    }

    public function isLowOnStock()
    {
        return $this->current_stock <= $this->max_stock;
    }

    public function updateStock($orderItem)
    {
        $stockChange = -$orderItem['quantity'] * $this->pivot->amount;
        $this->lockForUpdate()->decrement('current_stock', abs($stockChange));
    }

    public function shouldSendAlert()
    {
        return $this->current_stock < $this->max_stock && ! $this->alert_sent;
    }
}
