<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
    ];

    /**
     * このスクールキットに属する商品を直接取得
     */
    public function products(): BelongsToMany
    {
        // belongsToMany(相手のモデル名, 中間テーブル名, 自分のIDを示すカラム, 相手のIDを示すカラム)
        return $this->belongsToMany(Product::class, 'school_kit_items', 'school_kit_id', 'product_id');
    }

    //キットイメージ画像を表示させる
    public function getImageUrl()
    {
        // image_url が空の場合はデフォルト画像を返す
        if (!$this->image_url || $this->image_url->isNotEmpty()) {
            return asset('images/no-image.png');
        }

        return asset('storage/' . $this->image_url);
    }

    //キットの総合計金額（total_price）を表示するためのアクセサ
    public function getTotalPriceAttribute()
    {
        return $this->products->sum('price');
    }
}
