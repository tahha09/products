<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */


class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Electronics', 'Clothing', 'Books', 'Accessories', 'Home'];

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10, 999),
            'category' => $this->faker->randomElement($categories),
            'image' => null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
