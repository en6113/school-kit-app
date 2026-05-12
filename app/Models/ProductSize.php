<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'stock',
    ];

    /**
     * このサイズが属する商品を取得
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * サイズ名を取得
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class,'size_id');
    }
}
