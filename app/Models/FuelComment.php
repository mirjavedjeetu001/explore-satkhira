<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_station_id',
        'commenter_name',
        'commenter_phone',
        'comment',
    ];

    public function fuelStation()
    {
        return $this->belongsTo(FuelStation::class);
    }
}
