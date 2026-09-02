<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CelebrationCardGeneration extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'photo_path',
        'card_image_path',
        'download_format',
    ];
}
