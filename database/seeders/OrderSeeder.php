<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductSize;


class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 注文者となるユーザーをすべて取得
        $users = User::all();

        // 2. 注文を10件作成する
        Order::factory()->count(10)->create()->each(function ($order) use ($users) {

            // ランダムなユーザーを注文者に設定
            $order->update(['user_id' => $users->random()->id]);

            $total = 0;
            $itemCount = rand(1, 3);

            for ($i = 0; $i < $itemCount; $i++) {
                // すでにあるProductからランダムに1つ取得
                $product = Product::inRandomOrder()->first();

                // そのProductに紐づくSizeを1つ取得
                $productSize = ProductSize::where('product_id', $product->id)->inRandomOrder()->first();

                $quantity = rand(1, 2);
                $priceAtPurchase = $product->price;

                // 3. OrderDetailを作成
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_size_id' => $productSize->id ?? null,
                    'quantity' => $quantity,
                    'price_at_purchase' => $priceAtPurchase,
                ]);

                $total += $priceAtPurchase * $quantity;
            }

            // 4. Orderの合計金額を最終更新
            $order->update(['total_amount' => $total]);
        });
    }
}