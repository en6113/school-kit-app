<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Vendor;

class ProductPolicy
{
    /**
     * 商品を更新できるか
     */
    public function update(mixed $vendor, Product $product): bool
    {
        if (!$vendor instanceof Vendor) {
            return false;
        }

        return $vendor->id === $product->vendor_id;
    }

    /**
     * 商品を削除できるか
     */
    public function delete(mixed $vendor, Product $product): bool
    {
        if (!$vendor instanceof Vendor) {
            return false;
        }
        
        return $vendor->id === $product->vendor_id;
    }
}
