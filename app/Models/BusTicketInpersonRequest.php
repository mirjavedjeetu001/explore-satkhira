<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTicketInpersonRequest extends Model
{
    protected $fillable = [
        'bus_ticket_id',
        'buyer_name',
        'buyer_phone',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(BusTicket::class, 'bus_ticket_id');
    }
}
