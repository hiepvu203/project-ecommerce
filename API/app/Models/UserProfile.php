<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserProfile extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'avatar',
        'phone',
        'birthdate',
        'gender',
        'address',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo((User::class),'user_id');
    }

    public function getBirthdateAttribute($value) {
        return $value ? \Carbon\Carbon::parse($value)->toDateString() : null;
    }

    public function setBirthdateAttribute($value) {
        $this->attributes['birthdate'] = $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }
}
