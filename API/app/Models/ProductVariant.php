<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Product
 *
 * @property int $product_id
 * @property string $name
 * @property int $value
 * @property float $price_adjustment
 * @property float $price
 * @property int $quantity
 * @property string|null $sku
 */
class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'value',
        'price_adjustment',
        'quantity',
        'sku',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
