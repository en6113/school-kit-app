<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_amount' => 0,
            'delivery_method' => fake()->randomElement([1, 2]),
            'payment_method' => fake()->randomElement([1, 2]),
            'status' => fake()->randomElement([1, 2, 3]),
        ];
    }
}
