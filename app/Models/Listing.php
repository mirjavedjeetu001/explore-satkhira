<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'upazila_id',
        'title',
        'title_bn',
        'slug',
        'short_description',
        'description',
        'image',
        'gallery',
        'address',
        'phone',
        'email',
        'website',
        'facebook',
        'youtube',
        'latitude',
        'longitude',
        'map_embed',
        'opening_hours',
        'price_from',
        'price_to',
        'features',
        'extra_fields',
        'status',
        'is_featured',
        'views',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'gallery' => 'array',
        'opening_hours' => 'array',
        'features' => 'array',
        'extra_fields' => 'array',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments(): MorphMany
    {
        return $this->comments()->where('status', 'approved');
    }

    // Listing Images (Offers, Promotions, Banners)
    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->ordered();
    }

    public function approvedImages(): HasMany
    {
        return $this->hasMany(ListingImage::class)->approved()->active()->valid()->ordered();
    }

    public function pendingImages(): HasMany
    {
        return $this->hasMany(ListingImage::class)->pending()->ordered();
    }

    public function imagesByType(string $type): HasMany
    {
        return $this->approvedImages()->ofType($type);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByUpazila($query, $upazilaId)
    {
        return $query->where('upazila_id', $upazilaId);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAverageRating(): float
    {
        return $this->approvedComments()->whereNotNull('rating')->avg('rating') ?? 0;
    }
}
