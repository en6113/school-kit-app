<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vendor_id',
        'name',
        'price',
        'stock',
        'description',
    ];

    /**
     * この商品を取り扱っている業者を取得
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * この商品が属するカテゴリーを取得
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * この商品の画像を取得
     */
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * この商品のサイズ展開を取得
     */
    public function productSizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    /**
     * この商品が属するスターターキットを取得
     */
    public function starterKits(): BelongsToMany
    {
        return $this->belongsToMany(StarterKit::class, 'starter_kit_items');
    }
    /**
     * カート明細を取得
     */
    public function cartDetails(): HasMany
    {
        return $this->hasMany(CartDetail::class);
    }

    /**
     * 注文明細を取得
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * 注文状況（いつ注文されたか、支払い状態はどうか）を取得
     */
    public function orders(): BelongsToMany
    {
        // order_details テーブルを中間テーブルとして Order モデルと多対多
        return $this->belongsToMany(Order::class, 'order_details')
            ->withPivot('quantity', 'price_at_purchase', 'product_size_id')
            ->withTimestamps();
    }
}
