<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoStoreRating extends Model
{
    protected $fillable = [
        'mango_store_id',
        'phone',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(MangoStore::class, 'mango_store_id');
    }
}
