<?php

namespace Database\Seeders;

use App\Models\Ingredient;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $units = [
            // Weight units
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
                'category' => 'weight',
                'is_convertible' => true,
                'conversion_factor' => 1,
                'is_base_unit' => true,
            ],
            [
                'name' => 'Kilogram',
                'abbreviation' => 'kg',
                'category' => 'weight',
                'is_convertible' => true,
                'conversion_factor' => 1000,
                'is_base_unit' => false,
            ],
            // Volume units
            [
                'name' => 'Milliliter',
                'abbreviation' => 'ml',
                'category' => 'volume',
                'is_convertible' => true,
                'conversion_factor' => 1,
                'is_base_unit' => true,
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'L',
                'category' => 'volume',
                'is_convertible' => true,
                'conversion_factor' => 1000,
                'is_base_unit' => false,
            ],
            // Quantity units
            [
                'name' => 'Piece',
                'abbreviation' => 'pc',
                'category' => 'quantity',
                'is_convertible' => true,
                'conversion_factor' => 1,
                'is_base_unit' => true,
            ],
            [
                'name' => 'Dozen',
                'abbreviation' => 'dz',
                'category' => 'quantity',
                'is_convertible' => true,
                'conversion_factor' => 12,
                'is_base_unit' => false,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $kg = Unit::where('name', 'Kilogram')->first();
        $gm = Unit::where('name', 'Gram')->first();

        $ingredients = [
            [
                'name' => 'Beef',
                'current_stock' => 20000,
                'max_stock' => 20000,
                'stock_unit_id' => $gm->id,
                'alert_sent' => false,
            ],
            [
                'name' => 'Cheese',
                'current_stock' => 5000,
                'max_stock' => 5000,
                'stock_unit_id' => $gm->id,
                'alert_sent' => false,
            ],
            [
                'name' => 'Onion',
                'current_stock' => 1000,
                'max_stock' => 1000,
                'stock_unit_id' => $gm->id,
                'alert_sent' => false,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }

        $products = [
            [
                'name' => 'Cheese Burger',
                'description' => 'A burger with cheese',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $beef = Ingredient::where('name', 'Beef')->first();
        $cheese = Ingredient::where('name', 'Cheese')->first();
        $onion = Ingredient::where('name', 'Onion')->first();
        $burger = Product::where('name', 'Cheese Burger')->first();

        // 150 gram of beef, 30 gram of cheese, 20 gram of onion
        $burger->ingredients()->attach($beef, ['amount' => 150, 'unit_id' => $gm->id]);
        $burger->ingredients()->attach($cheese, ['amount' => 30, 'unit_id' => $gm->id]);
        $burger->ingredients()->attach($onion, ['amount' => 20, 'unit_id' => $gm->id]);
    }
}
