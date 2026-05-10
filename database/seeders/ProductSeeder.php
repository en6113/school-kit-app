<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_ids' => [1,12],
                'vendor_id' => 1,
                'name' => '体操服（サイズあり）',
                'price'=> 1500,
                'stock' => 50,
            ],
            [
                'category_ids' => [1,2,3,4,5,6,15],
                'vendor_id' => 2,
                'name' => '上靴（サイズあり）',
                'price'=> 800,
                'stock' => 50,
            ],
            [
                'category_ids' => [1,2,3,4,5,6,20],
                'vendor_id' => 3,
                'name' => '名札',
                'price'=> 100,
                'stock' => 500,
            ],
            [
                'category_ids' => [1,10],
                'vendor_id' => 5,
                'name' => '鍵盤ハーモニカ',
                'price'=> 2000,
                'stock' => 50,
            ],
            [
                'category_ids' => [2,11],
                'vendor_id' => 5,
                'name' => '絵の具道具',
                'price'=> 2300,
                'stock' => 50,
            ],
        ];

        foreach ($products as $data) {
            // 'category_ids' だけを除いた配列で商品を保存
            $productData = collect($data)->except('category_ids')->toArray();

            $newProduct = \App\Models\Product::create($productData);

            // 紐付け
            $newProduct->categories()->attach($data['category_ids']);
        }
    }
}