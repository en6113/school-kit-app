<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StarterKit;

class StarterKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Kits = [
            ['name' => '1年生スターターキット', 'description' => '新入生に必要な基本セットです'],
            ['name' => '2年生スターターキット', 'description' => '2年生で使うドリルやテスト、絵具道具やかけ算カードなどが含まれるセットです'],
            ['name' => '3年生スターターキット', 'description' => '3年生で使うドリルやテスト、習字道具やリコーダーが含まれるセットです'],
            ['name' => '4年生スターターキット', 'description' => '4年生で使うドリルやテスト、彫刻刀やコンパス・分度器などが含まれるセットです'],
            ['name' => '5年生スターターキット', 'description' => '5年生で使うドリルやテスト、裁縫セットなどが含まれるセットです'],
            ['name' => '6年生スターターキット', 'description' => '6年生で使うドリルやテストが含まれるセットです'],
        ];

        foreach ($Kits as $kit) {
            StarterKit::create($kit);
        }
    }
}
