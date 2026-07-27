<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSize;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart_detail>
 */
class CartDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::inRandomOrder()->first()?->id ?? Cart::factory(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'product_size_id' => null,
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}
