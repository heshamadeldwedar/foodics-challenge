<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('quantity', 'price')
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
