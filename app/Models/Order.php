<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    ];

    protected $casts = [
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function updateProductStock()
    {
        foreach ($this->products as $product) {
            $product->updateStock();
        }
    }

    public function canBeFulfilled()
    {
        foreach ($this->products as $product) {
            foreach ($product->ingredients as $ingredient) {
                $requiredAmount = $ingredient->pivot->amount * $product->pivot->quantity;
                if ($ingredient->stock < $requiredAmount) {
                    return false;
                }
            }
        }

        return true;
    }
}
