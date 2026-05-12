<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductSize;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productSizes = [
            ['product_id' => 2, 'size_ids' => range(1, 7)],
            ['product_id' => 3, 'size_ids' => range(1, 7)],
            ['product_id' => 4, 'size_ids' => range(8, 21)],
        ];

        foreach ($productSizes as $productSize) {
            foreach ($productSize['size_ids'] as $size_id) {
                ProductSize::create([
                    'product_id' => $productSize['product_id'],
                    'size_id' => $size_id,
                    'stock' => 50,
                ]);
            }
        }
    }
}