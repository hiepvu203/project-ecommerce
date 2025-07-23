<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'discount_percent',
        'discount_amount',
        'start_at',
        'end_at',
        'active',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sale_product');
    }

    // Scope: chỉ lấy sale đang active
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    // Scope: chỉ lấy sale còn hiệu lực thời gian
    public function scopeValid(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where('start_at', '<=', $now)->where('end_at', '>=', $now);
    }
}
