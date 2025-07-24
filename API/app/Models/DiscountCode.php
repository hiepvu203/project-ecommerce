<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\DiscountCode
 *
 * @property int $id
 * @property int $shop_id
 * @property string $code
 * @property string $type
 * @property float $value
 * @property float|null $min_order_amount
 * @property int|null $usage_limit
 * @property int $used
 * @property int|null $usage_per_user
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'usage_per_user',
        'start_at',
        'end_at',
        'active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Scope: chỉ lấy mã đang active
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    // Scope: chỉ lấy mã còn hiệu lực thời gian
    public function scopeValid(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where('start_at', '<=', $now)->where('end_at', '>=', $now);
    }

    // Kiểm tra điều kiện sử dụng mã
    public function canUse(float $orderAmount, int $userId): bool
    {
        if (!$this->active) return false;
        $now = Carbon::now();
        if ($this->start_at > $now || $this->end_at < $now) return false;
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) return false;
        return true;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_code', 'code');
    }
}
