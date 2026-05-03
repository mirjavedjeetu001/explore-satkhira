<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTicket extends Model
{
    protected $fillable = [
        'bus_ticket_seller_id',
        'from_location',
        'to_location',
        'journey_date',
        'bus_name',
        'ticket_type',
        'seat_count',
        'price_per_ticket',
        'description',
        'contact_number',
        'whatsapp_number',
        'is_sold',
        'interested_count',
        'sold_at',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'interested_count' => 'integer',
        'seat_count' => 'integer',
        'price_per_ticket' => 'decimal:2',
        'journey_date' => 'date',
        'sold_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(BusTicketSeller::class, 'bus_ticket_seller_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_sold', false);
    }

    public function scopeSold($query)
    {
        return $query->where('is_sold', true);
    }

    public function markAsSold()
    {
        $this->update([
            'is_sold' => true,
            'sold_at' => now(),
        ]);
    }

    public function incrementInterested()
    {
        $this->increment('interested_count');
    }

    public function getTicketTypeLabel(): string
    {
        return match($this->ticket_type) {
            'ac' => 'এসি বাস',
            'sleeper' => 'স্লিপার',
            'deluxe' => 'ডিলাক্স',
            default => 'নন-এসি',
        };
    }
}
