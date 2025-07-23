<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'parent_id',
        'order_position',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order_position' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(SystemCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SystemCategory::class, 'parent_id');
    }


    public function category()
    {
        return $this->belongsTo(SystemCategory::class, 'category_id');
    }
}
