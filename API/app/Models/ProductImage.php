<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image',
        'order_position',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
