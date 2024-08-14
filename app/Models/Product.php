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

    public function updateStock()
    {
        foreach ($this->ingredients as $ingredient) {
            $ingredientUnit = Unit::find($ingredient->pivot->unit_id);
            $stockUnit = $ingredient->stockUnit;

            $amount = $ingredient->pivot->amount;
            if ($ingredientUnit->id !== $stockUnit->id) {
                $amount = $ingredientUnit->convert($amount, $stockUnit);
            }

            $ingredient->stock -= $amount;
            $ingredient->save();
        }
    }
}
