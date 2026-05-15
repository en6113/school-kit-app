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
                'size_name' => '110cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '120cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '130cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '140cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '150cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '160cm',
                'type' => 'clothes',
            ],
                        [
                'size_name' => '170cm',
                'type' => 'clothes',
            ],
            [
                'size_name' => '(上靴用)18cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)18.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)19cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)19.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)20cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)20.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)21cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)21.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)22cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)22.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)23cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)23.5cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)24cm',
                'type' => 'shoes',
            ],
            [
                'size_name' => '(上靴用)24.5cm',
                'type' => 'shoes',
            ],
        ];

        Size::insert($Sizes);
    }
}
