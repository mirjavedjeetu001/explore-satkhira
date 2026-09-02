<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CelebrationCardRecipient extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'photo_path',
    ];
}
