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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

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
                'is_convertible' => false,
                'conversion_factor' => null,
                'is_base_unit' => false,
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
                'stock' => 20,
                'max_stock' => 20,
                'stock_unit_id' => $kg->id,
                'alert_sent' => false,
            ],
            [
                'name' => 'Cheese',
                'stock' => 5,
                'max_stock' => 5,
                'stock_unit_id' => $kg->id,
                'alert_sent' => false,
            ],
            [
                'name' => 'Onion',
                'stock' => 1,
                'max_stock' => 1,
                'stock_unit_id' => $kg->id,
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
        $burger->ingredients()->attach($beef, ['quantity' => 150, 'unit_id' => $gm->id]);
        $burger->ingredients()->attach($cheese, ['quantity' => 30, 'unit_id' => $gm->id]);
        $burger->ingredients()->attach($onion, ['quantity' => 20, 'unit_id' => $gm->id]);
    }
}
