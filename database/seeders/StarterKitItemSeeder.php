<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StarterKit;
use App\Models\Product;

class StarterKitItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starterKits = StarterKit::all();

        foreach ($starterKits as $kit) {
            // 各キットごとにランダムな5つの商品IDを取得
            $productIds = Product::inRandomOrder()->limit(5)->pluck('id');
            // リレーションを使って紐付ける
            $kit->products()->sync($productIds);
        }
    }
}