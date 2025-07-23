<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Product
 *
 * @property int $cart_id
 * @property int $product_id
 * @property int|null $variant_id
 * @property int $quantity
 * @property float $price
 * @property float $price_at_added
 */
class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity', 'price_at_added'];


    protected $casts = [
        'quantity' => 'integer',
        'price_at_added' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getTotalPriceAttribute(): float
    {
        $price = $this->variant ? $this->variant->price : $this->product->price;
        return $price * $this->quantity;
    }
}
