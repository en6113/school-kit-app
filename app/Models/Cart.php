<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function getTotalAmount()
    {
        $total = 0;
        // リレーション経由で全明細をループ
        foreach ($this->cartDetails as $detail) {
            $total += $detail->product->price * $detail->quantity;
        }
        return $total;
    }
}
