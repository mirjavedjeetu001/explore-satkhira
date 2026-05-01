<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoProduct extends Model
{
    protected $fillable = [
        'mango_store_id',
        'name',
        'price_per_kg',
        'min_order_kg',
        'image',
        'description',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price_per_kg' => 'decimal:2',
        'min_order_kg' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(MangoStore::class, 'mango_store_id');
    }
}
