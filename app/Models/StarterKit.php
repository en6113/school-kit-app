<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StarterKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * このスターターキットに属する商品一覧を直接取得
     */
    public function products(): BelongsToMany
    {
        // belongsToMany(相手のモデル名, 中間テーブル名, 自分のIDを示すカラム, 相手のIDを示すカラム)
        return $this->belongsToMany(Product::class, 'starter_kit_items', 'starter_kit_id', 'product_id');
    }
}
