<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
            ->withPivot('amount')
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
            $stockChange = $quantity * ($ingredient->pivot->amount);
            if ($ingredient->current_stock < $stockChange) {
                return false;
            }
        }

        return true;
    }

    public function updateStock($quantity)
    {
        $updates = [];
        foreach ($this->ingredients as $ingredient) {
            $stockChange = $quantity * $ingredient->pivot->amount;
            $updates[] = [
                'id' => $ingredient->id,
                'stock_change' => $stockChange,
            ];
        }
        if (! $this->haveEnoughStock($quantity)) {
            throw new Exception('Not enough stock');
        }
        Collection::make($updates)->each(function ($update) {
            Ingredient::where('id', $update['id'])
                ->lockForUpdate()
                ->decrement('current_stock', $update['stock_change']);
        });
    }
}
