<?php

namespace App\Faker;

use Faker\Provider\Base;

class ProductProvider extends Base
{
    // 商品名のリストを定義
    protected static $productNames = [
        '体操服（サイズあり）',
        '体操ズボン（サイズあり）',
        '体操帽子',
        '上靴（サイズあり）',
        '名札',
        '体操服用名札',
        '道具箱',
        'クレヨン',
        'クーピー',
        'はさみ',
        'のり',
        'ネームペン',
        'えんぴつ',
        '消しゴム',
        'おはじき',
        'ブロック',
        '計算ドリル',
        '漢字ドリル',
        'テスト',
        'ノート（サイズあり）',
        'タブレット',
        '鍵盤ハーモニカ',
        '絵の具道具',
        '習字道具',
        'リコーダー',
        '彫刻刀',
        '分度器',
        'コンパス',
        '裁縫セット',
    ];

    /**
     * $faker->productName で呼び出せるようになるメソッド
     */
    public function productName(): string
    {
        return static::randomElement(static::$productNames);
    }
}