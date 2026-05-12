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
                'category_ids' => [1, 2, 3, 4, 5, 6, 20],
                'vendor_id' => 3,
                'name' => '名札',
                'price' => 100,
                'total_stock' => 500,
            ],
            [
                'category_ids' => [1,2,3,4,5,6,12],
                'vendor_id' => 1,
                'name' => '体操服（サイズあり）',
                'price'=> 1000,
                'total_stock' => 0,
            ],
            [
                'category_ids' => [1,2,3,4,5,6,12],
                'vendor_id' => 1,
                'name' => '体操ズボン（サイズあり）',
                'price' => 1200,
                'total_stock' => 0,
            ],
            [
                'category_ids' => [1,2,3,4,5,6,15],
                'vendor_id' => 2,
                'name' => '上靴（サイズあり）',
                'price'=> 800,
                'total_stock' => 0,
            ],
            [
                'category_ids' => [1,10],
                'vendor_id' => 4,
                'name' => '鍵盤ハーモニカ',
                'price'=> 2000,
                'total_stock' => 100,
            ],
            [
                'category_ids' => [2,11],
                'vendor_id' => 5,
                'name' => '絵の具道具',
                'price'=> 2300,
                'total_stock' => 100,
            ],
            [
                'category_ids' => [3, 13],
                'vendor_id' => 5,
                'name' => '習字道具',
                'price' => 2000,
                'total_stock' => 100,
            ],
            [
                'category_ids' => [4, 11],
                'vendor_id' => 5,
                'name' => '彫刻刀',
                'price' => 1000,
                'total_stock' => 100,
            ],
            [
                'category_ids' => [5, 9],
                'vendor_id' => 5,
                'name' => '裁縫道具',
                'price' => 2200,
                'total_stock' => 100,
            ],
        ];

        //　category_idsを配列で書いているため、そのままcreateすると入らない。
        foreach ($products as $data) {
            // 1.'category_ids' だけを除いた配列で商品を保存
            $productData = collect($data)->except('category_ids')->toArray();

            $newProduct = Product::create($productData);

            // 2.category_idを紐付け
            $newProduct->categories()->attach($data['category_ids']);
        }
    }
}