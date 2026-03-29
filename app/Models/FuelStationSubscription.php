<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelStationSubscription extends Model
{
    protected $fillable = [
        'fuel_station_id',
        'push_subscription_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function fuelStation()
    {
        return $this->belongsTo(FuelStation::class);
    }

    public function pushSubscription()
    {
        return $this->belongsTo(PushSubscription::class);
    }
}
