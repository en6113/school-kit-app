<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productImages = [
            [
                'product_id' => 1,
                'image_url' => 'https://example.com/images/product1.jpg',
                'sort_number' => 1,
            ],
            [
                'product_id' => 1,
                'image_url' => 'https://example.com/images/product1_2.jpg',
                'sort_number' => 2,
            ],
            [
                'product_id' => 1,
                'image_url' => 'https://example.com/images/product1_3.jpg',
                'sort_number' => 3,
            ],
            [
                'product_id' => 2,
                'image_url' => 'https://example.com/images/product2.jpg',
                'sort_number' => 1,
            ],
            [
                'product_id' => 3,
                'image_url' => 'https://example.com/images/product3.jpg',
                'sort_number' => 1,
            ],
            [
                'product_id' => 4,
                'image_url' => 'https://example.com/images/product4.jpg',
                'sort_number' => 1,
            ],
            [
                'product_id' => 5,
                'image_url' => 'https://example.com/images/product5.jpg',
                'sort_number' => 1,
            ],
        ];

        foreach ($productImages as $productImage) {
            ProductImage::insert($productImage);
        }

    }
}