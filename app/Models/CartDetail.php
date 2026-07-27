<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_size_id',
        'quantity',
    ];
    
    /**
     * この明細が属する商品を紐付け
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * この明細が属するサイズ情報を紐付け
     */
    public function productSize(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    /**
     * 親であるカート本体を紐付け
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
