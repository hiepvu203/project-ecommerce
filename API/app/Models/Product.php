<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $shop_id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property float|null $compare_price
 * @property int $quantity
 * @property string|null $sku
 * @property string $status
 * @property string|null $rejection_reason
 * @property bool $is_featured
 */
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shop_id', 'category_id', 'name', 'slug', 'description', 'price', 'compare_price',
        'quantity', 'sku', 'status', 'rejection_reason', 'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function category()
    {
        return $this->belongsTo(SystemCategory::class, 'category_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_product');
    }
}
