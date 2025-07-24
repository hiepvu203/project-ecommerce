<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Summary of Order
 * @property int $id
 * @property string $status
 */
class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'shipping_fee',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'status',
        'notes',
        'discount_code',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function subOrders(): HasMany
    {
        return $this->hasMany(SubOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderNumber(): Attribute
    {
        return Attribute::make(
            get: fn($value) => '#' . $value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
