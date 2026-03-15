<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EidCard extends Model
{
    protected $fillable = [
        'phone',
        'name',
        'designation',
        'custom_message',
        'template',
        'photo',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
