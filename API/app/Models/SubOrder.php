<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\SubOrder
 *
 * @property int $id
 * @property int $order_id
 * @property int $shop_id
 * @property float $subtotal
 * @property float $shipping_fee
 * @property float $commission
 * @property float $total
 * @property string $status
 * @property string $status_customer
 * @property string|null $cancellation_reason
 * @property string|null $tracking_number
 * @property string|null $shipping_carrier
 * @property string|null $shipping_method
 * @property string|null $status_customer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */

class SubOrder extends Model
{
    protected $fillable = [
        'order_id',
        'shop_id',
        'subtotal',
        'shipping_fee',
        'commission',
        'total',
        'status',
        'status_customer',
        'cancellation_reason',
        'tracking_number',
        'shipping_carrier',
        'shipping_method',
        'status_customer'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'commission' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'sub_order_id');
    }
}
