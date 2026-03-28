<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_station_id',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'edit_pin',
        'session_id',
        'petrol_available',
        'diesel_available',
        'octane_available',
        'petrol_price',
        'petrol_selling_price',
        'diesel_price',
        'diesel_selling_price',
        'octane_price',
        'octane_selling_price',
        'fixed_amount',
        'queue_status',
        'notes',
        'image',
        'images',
        'is_verified',
        'correct_votes',
        'incorrect_votes',
    ];

    protected $casts = [
        'petrol_available' => 'boolean',
        'diesel_available' => 'boolean',
        'octane_available' => 'boolean',
        'is_verified' => 'boolean',
        'petrol_price' => 'decimal:2',
        'diesel_price' => 'decimal:2',
        'octane_price' => 'decimal:2',
        'petrol_selling_price' => 'decimal:2',
        'diesel_selling_price' => 'decimal:2',
        'octane_selling_price' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'images' => 'array',
    ];

    public function fuelStation()
    {
        return $this->belongsTo(FuelStation::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function getQueueStatusBanglaAttribute()
    {
        return match($this->queue_status) {
            'none' => 'কোন লাইন নেই',
            'short' => 'ছোট লাইন',
            'medium' => 'মাঝারি লাইন',
            'long' => 'বড় লাইন',
            default => 'অজানা',
        };
    }

    public function getAvailabilityStatusAttribute()
    {
        if ($this->petrol_available || $this->diesel_available || $this->octane_available) {
            return 'available';
        }
        return 'unavailable';
    }
}
