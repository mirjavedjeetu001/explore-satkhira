<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewspaperEdition extends Model
{
    protected $fillable = [
        'listing_id',
        'uploaded_by',
        'edition_date',
        'pages',
        'pdf_file',
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'pages' => 'array',
        'edition_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
