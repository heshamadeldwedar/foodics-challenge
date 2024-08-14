<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'stock_unit_id',
        'alert_threshold',
        'alert_sent'
    ];

    protected $casts = [
        'stock' => 'decimal:3',
        'alert_threshold' => 'decimal:3',
        'alert_sent' => 'boolean'
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
        return $this->stock <= $this->alert_threshold;
    }

    public function updateStock($amount, Unit $unit)
    {
        if ($unit->id !== $this->stock_unit_id) {
            $amount = $unit->convert($amount, $this->stockUnit);
        }

        $this->stock += $amount;
        $this->save();
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= alert_threshold');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }
}
