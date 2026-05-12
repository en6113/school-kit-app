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
        'total_stock',
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
     * 商品のカテゴリーが衣服または履物だった場合にサイズタイプ（clothing or shoes）を取得
     */
    public function getSizeTypeAttribute()
    {
        $categoryNames = $this->categories->pluck('name')->toArray();

        if (in_array('履物', $categoryNames)) {
            return 'shoes';
        }

        if (in_array('衣服', $categoryNames)) {
            return 'clothing';
        }

        return null;
    }

    /**
     * この商品のサイズ展開(在庫数)を取得
     */
    public function productSizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    /**
     * この商品に関連するサイズを取得
     */
    public function sizes(): BelongsToMany
    {
        // 第2引数は中間テーブル名、第3はProduct側の外部キー、第4はSize側の外部キー
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id');
    }

    /**
     * この商品の画像を取得
     */
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
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
