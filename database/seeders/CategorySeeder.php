<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            '1年生',
            '2年生',
            '3年生',
            '4年生',
            '5年生',
            '6年生',
            '国語',
            '算数',
            '家庭科',
            '音楽',
            '図工',
            '体育',
            '書写',
            '衣服',
            '履物',
            '文房具',
            'ドリル',
            'テスト',
            '課題図書',
            'その他',
        ];

        foreach ($names as $name) {
            Category::create(['name' => $name]);
        }
    }
}
