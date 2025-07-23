<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantService
{
    public function createVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {
            ProductVariant::create([
                'product_id'        => $product->id,
                'name'              => $variant['name'],
                'value'             => $variant['value'],
                'price_adjustment'  => $variant['price_adjustment'] ?? 0,
                'quantity'          => $variant['quantity'],
                'sku'               => $variant['sku'] ?? null,
            ]);
        }
    }
}
