<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'upazila_id',
        'address',
        'google_map_link',
        'phone',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function reports()
    {
        return $this->hasMany(FuelReport::class);
    }

    public function latestReport()
    {
        return $this->hasOne(FuelReport::class)->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
