<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalamiEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_name',
        'phone',
        'giver_name',
        'giver_relation',
        'amount',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get entries by session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get total amount by session
     */
    public static function getTotalBySession($sessionId)
    {
        return self::where('session_id', $sessionId)->sum('amount');
    }
}
