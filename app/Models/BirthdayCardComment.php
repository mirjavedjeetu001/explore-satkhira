<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirthdayCardComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'birthday_card_id',
        'visitor_name',
        'visitor_phone',
        'wish_message',
    ];

    public function birthdayCard(): BelongsTo
    {
        return $this->belongsTo(BirthdayCard::class);
    }
}
