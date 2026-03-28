<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'title', 'body', 'icon', 'url', 'image', 'total_sent', 'total_failed', 'sent_by',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
