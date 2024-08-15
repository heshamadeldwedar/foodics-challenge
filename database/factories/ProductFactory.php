<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Unit;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
        ];
    }

    public function configure() {
        return $this->afterCreating(function (Product $product) {
            $gm = Unit::where('name', 'Gram')->first();
            $onion = Ingredient::where('name', 'Onion')->first();


            $product->ingredients()->attach(
                $onion,
                ['unit_id' => $gm->id, 'amount'=> '500']
            );
        });
    }
}

