<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'country',
        'city',
        'device',
        'browser',
        'platform',
        'user_id',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visit_date', now()->month)
                     ->whereYear('visit_date', now()->year);
    }

    public function scopeUniqueVisitors($query)
    {
        return $query->distinct('ip_address');
    }
}
