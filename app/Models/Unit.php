<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'category',
        'is_convertible',
        'conversion_factor',
        'is_base_unit',
    ];

    protected $casts = [
        'is_convertible' => 'boolean',
        'is_base_unit' => 'boolean',
        'conversion_factor' => 'float',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'stock_unit_id');
    }

    public function productIngredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function getConversionFactor($toUnit)
    {
        if (!$this->is_convertible || !$toUnit->is_convertible) {
            return null;
        }

        if ($this->category !== $toUnit->category) {
            return null;
        }

        return $this->conversion_factor / $toUnit->conversion_factor;
    }

    public function convert($amount, $toUnit)
    {
        $factor = $this->getConversionFactor($toUnit);

        if ($factor === null) {
            return null;
        }

        return $amount * $factor;
    }

    public static function getBaseUnit($category)
    {
        return self::where('category', $category)
            ->where('is_base_unit', true)
            ->first();
    }
}
