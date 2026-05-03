<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTicketSeller extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'password',
        'whatsapp',
        'upazila_id',
        'address',
        'is_active',
        'view_count',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'view_count' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(BusTicket::class)->latest();
    }

    public function availableTickets()
    {
        return $this->hasMany(BusTicket::class)->where('is_sold', false)->latest();
    }

    public function soldTickets()
    {
        return $this->hasMany(BusTicket::class)->where('is_sold', true)->latest();
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
