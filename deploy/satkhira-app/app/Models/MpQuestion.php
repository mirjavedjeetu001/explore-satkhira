<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpQuestion extends Model
{
    protected $fillable = [
        'mp_profile_id',
        'user_id',
        'name',
        'email',
        'phone',
        'question',
        'answer',
        'status',
        'is_public',
        'answered_at',
        'answered_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function mpProfile(): BelongsTo
    {
        return $this->belongsTo(MpProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'answered']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
