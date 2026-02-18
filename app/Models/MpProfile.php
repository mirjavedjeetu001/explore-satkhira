<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MpProfile extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'designation',
        'constituency',
        'bio',
        'image',
        'phone',
        'email',
        'facebook',
        'twitter',
        'address',
        'elected_date',
        'is_active',
    ];

    protected $casts = [
        'elected_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(MpQuestion::class);
    }

    public function approvedQuestions(): HasMany
    {
        return $this->questions()->whereIn('status', ['approved', 'answered']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
