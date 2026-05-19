<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Size;

class SizeFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['shoes', 'clothes']);

        if ($type === 'shoes') {
            // (36〜50 のランダムな整数を 2 で割ることで、18.0 から 25.0 までの0.5刻みを再現)
            $sizeNumber = fake()->numberBetween(36, 50) / 2;
            $sizeName = '(上靴用)' . $sizeNumber . 'cm';
        } else {
            // (22〜34 のランダムな整数を 5 倍することで、110 から 170 までの5刻みを再現)
            $sizeNumber = fake()->numberBetween(22, 34) * 5;
            $sizeName = $sizeNumber . 'cm';
        }

        return [
            'size_name' => $sizeName,
            'type' => $type,
        ];
    }
}
