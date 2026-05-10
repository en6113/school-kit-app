<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSize;

class ProductSizeSeeder extends Seeder
{

    public function run(): void
    {
        $productSizes = [
            [
                'product_id' => 1,
                'size' => '110',
                'stock' => '50'
            ],
            [
                'product_id' => 1,
                'size' => '120',
                'stock' => '50'
            ],
            [
                'product_id' => 1,
                'size' => '130',
                'stock' => '50'
            ],
            [
                'product_id' => 1,
                'size' => '140',
                'stock' => '50'
            ],
            [
                'product_id' => 1,
                'size' => '150',
                'stock' => '50'
            ],
            [
                'product_id' => 1,
                'size' => '160',
                'stock' => '50'
            ],
                        [
                'product_id' => 1,
                'size' => '170',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)18',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)18.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)19',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)19.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)20',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)20.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)21',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)21.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)22',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)22.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)23',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)23.5',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)24',
                'stock' => '50'
            ],
            [
                'product_id' => 2,
                'size' => '(上靴用)24.5',
                'stock' => '50'
            ],
        ];

        ProductSize::insert($productSizes);
    }
}
