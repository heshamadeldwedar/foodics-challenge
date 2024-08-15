<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot('unit_id')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function haveEnoughStock($quantity)
    {
        foreach ($this->ingredients as $ingredient) {
            error_log(print_r($quantity, true));
            error_log(print_r($ingredient->amount, true));
            $stockChange = $quantity * $ingredient->amount;
            if ($ingredient->current_stock < $stockChange) {
                return false;
            }
        }
        return true;
    }

    public function updateStock($quantity)
    {
        foreach ($this->ingredients as $ingredient) {
            $stockChange = $quantity * $ingredient->pivot->unit->conversion_factor;
        }
    }
}
