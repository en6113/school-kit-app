<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

//テスト用に作成、通常開発時はSeederで分かりやすい商品名を作成
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'name' => 'テスト用商品_' . fake()->unique()->numberBetween(1, 10000),
            'price' => fake()->numberBetween(200, 5000),
            'total_stock' => fake()->numberBetween(10, 500),
        ];
    }

    //状態：在庫切れの商品テスト用
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'total_stock' => 0,
        ]);
    }
}
