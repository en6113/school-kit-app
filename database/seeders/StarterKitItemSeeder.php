<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StarterKitItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starterKits = [
            ['starter_kit_id' => 1, 'product_id' => 1],
            ['starter_kit_id' => 1, 'product_id' => 2],
            ['starter_kit_id' => 1, 'product_id' => 3],
            ['starter_kit_id' => 1, 'product_id' => 4],
            ['starter_kit_id' => 1, 'product_id' => 5],
            ['starter_kit_id' => 2, 'product_id' => 1],
            ['starter_kit_id' => 2, 'product_id' => 6],
            ['starter_kit_id' => 3, 'product_id' => 1],
            ['starter_kit_id' => 3, 'product_id' => 7],
            ['starter_kit_id' => 4, 'product_id' => 1],
            ['starter_kit_id' => 4, 'product_id' => 8],
            ['starter_kit_id' => 5, 'product_id' => 1],
            ['starter_kit_id' => 5, 'product_id' => 9],
            ['starter_kit_id' => 6, 'product_id' => 1],
        ];

        DB::table('starter_kit_items')->insert($starterKits);
    }
}