<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class News extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'title_bn',
        'slug',
        'excerpt',
        'content',
        'image',
        'gallery',
        'type',
        'event_date',
        'is_featured',
        'is_active',
        'views',
    ];

    protected $casts = [
        'gallery' => 'array',
        'event_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    public function scopeNotices($query)
    {
        return $query->where('type', 'notice');
    }

    public function scopeEvents($query)
    {
        return $query->where('type', 'event');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
