<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_size_id',
        'quantity',
        'price_at_purchase',
    ];

    // 明細から商品へのリレーション
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // 明細からサイズへのリレーション
    public function productSize(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
}
