<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{

    public function run(): void
    {
        $Sizes = [
            [
                'size_name' => '110',
                'type' => 'clothes',
            ],
            [
                'size_name' => '120',
                'type' => 'clothes',
            ],
            [
                'size_name' => '130',
                'type' => 'clothes',
            ],
            [
                'size_name' => '140',
                'type' => 'clothes',
            ],
            [
                'size_name' => '150',
                'type' => 'clothes',
            ],
            [
                'size_name' => '160',
                'type' => 'clothes',
            ],
                        [
                'size_name' => '170',
                'type' => 'clothes',
            ],
            [
                'size_name' => '(上靴用)18',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)18.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)19',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)19.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)20',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)20.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)21',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)21.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)22',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)22.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)23',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)23.5',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)24',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)24.5',
                'type' => 'shoes',
            ],
        ];

        Size::insert($Sizes);
    }
}
