<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_name',
        'type',
    ];

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
