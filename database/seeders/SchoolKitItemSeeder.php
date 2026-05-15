<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolKitItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolKits = [
            ['school_kit_id' => 1, 'product_id' => 1],
            ['school_kit_id' => 1, 'product_id' => 2],
            ['school_kit_id' => 1, 'product_id' => 3],
            ['school_kit_id' => 1, 'product_id' => 4],
            ['school_kit_id' => 1, 'product_id' => 5],
            ['school_kit_id' => 2, 'product_id' => 1],
            ['school_kit_id' => 2, 'product_id' => 6],
            ['school_kit_id' => 3, 'product_id' => 1],
            ['school_kit_id' => 3, 'product_id' => 7],
            ['school_kit_id' => 4, 'product_id' => 1],
            ['school_kit_id' => 4, 'product_id' => 8],
            ['school_kit_id' => 5, 'product_id' => 1],
            ['school_kit_id' => 5, 'product_id' => 9],
            ['school_kit_id' => 6, 'product_id' => 1],
        ];

        DB::table('school_kit_items')->insert($schoolKits);
    }
}