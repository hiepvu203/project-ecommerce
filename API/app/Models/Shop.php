<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop
 *
 * @property int $id
 * @property string $status
 * @property string $name
 * ...
 */
class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'logo_url', 'cover_image_url',
        'phone', 'address', 'city', 'country', 'status',
        'payment_methods', 'shipping_config', 'commission_rate'
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'shipping_config' => 'array',
        'commission_rate'  => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function verification()
    {
        return $this->hasOne(ShopVerification::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('status', StatusEnum::ACTIVE)->latest()->take(10);
    }
}
