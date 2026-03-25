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
        'diesel_price',
        'octane_price',
        'queue_status',
        'notes',
        'is_verified',
    ];

    protected $casts = [
        'petrol_available' => 'boolean',
        'diesel_available' => 'boolean',
        'octane_available' => 'boolean',
        'is_verified' => 'boolean',
        'petrol_price' => 'decimal:2',
        'diesel_price' => 'decimal:2',
        'octane_price' => 'decimal:2',
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
