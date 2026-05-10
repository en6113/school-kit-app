<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order_detail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'product_size_id' => ProductSize::inRandomOrder()->first()?->id ?? ProductSize::factory(),
            'quantity' => 1,
            'price_at_purchase' => null,
        ];
    }
}
