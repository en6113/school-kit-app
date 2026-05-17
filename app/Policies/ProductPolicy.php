<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Vendor;

class ProductPolicy
{
    /**
     * 商品を更新できるか
     */
    public function update(Vendor $vendor, Product $product): bool
    {
        return $vendor->id === $product->vendor_id;
    }

    /**
     * 商品を削除できるか
     */
    public function delete(Vendor $vendor, Product $product): bool
    {
        return $vendor->id === $product->vendor_id;
    }
}
