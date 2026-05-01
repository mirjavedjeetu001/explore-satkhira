<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoStore extends Model
{
    protected $fillable = [
        'owner_name',
        'store_name',
        'phone',
        'password',
        'upazila_id',
        'address',
        'description',
        'delivery_info',
        'whatsapp',
        'facebook_url',
        'logo',
        'is_active',
        'view_count',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'view_count' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(MangoProduct::class)->orderBy('sort_order');
    }

    public function availableProducts()
    {
        return $this->hasMany(MangoProduct::class)->where('is_available', true)->orderBy('sort_order');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
