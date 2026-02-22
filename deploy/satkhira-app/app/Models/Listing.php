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
        'application_deadline',
        'event_start_date',
        'event_end_date',
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
        'application_deadline' => 'date',
        'event_start_date' => 'date',
        'event_end_date' => 'date',
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

    /**
     * Scope to filter out expired job circulars (category_id = 21)
     * Job circulars with past deadline are hidden, other categories unaffected
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('category_id', '!=', 21) // Not a job circular
              ->orWhereNull('application_deadline') // No deadline set
              ->orWhere('application_deadline', '>=', now()->toDateString()); // Deadline not passed
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Check if event has ended (for Events category)
     */
    public function isEventEnded(): bool
    {
        if ($this->category_id != 22 || !$this->event_end_date) {
            return false;
        }
        return $this->event_end_date->isPast();
    }

    /**
     * Check if event is upcoming (hasn't started yet)
     */
    public function isEventUpcoming(): bool
    {
        if ($this->category_id != 22 || !$this->event_start_date) {
            return false;
        }
        return $this->event_start_date->isFuture();
    }

    /**
     * Check if event is currently ongoing
     */
    public function isEventOngoing(): bool
    {
        if ($this->category_id != 22 || !$this->event_start_date || !$this->event_end_date) {
            return false;
        }
        return now()->between($this->event_start_date, $this->event_end_date);
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

    /**
     * Find similar/duplicate listings based on phone or title
     */
    public static function findSimilar($phone = null, $title = null, $categoryId = null, $excludeId = null)
    {
        $similar = [
            'exact_phone' => collect(),
            'similar_title' => collect(),
        ];

        $query = self::with(['category', 'upazila', 'user']);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Find exact phone matches (most reliable)
        if ($phone) {
            // Normalize phone - remove spaces, dashes, +88, etc
            $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($normalizedPhone) >= 10) {
                // Get last 10 digits
                $phoneEnd = substr($normalizedPhone, -10);
                
                $phoneMatches = (clone $query)
                    ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+88', ''), '+', '') LIKE ?", ['%' . $phoneEnd])
                    ->where('status', '!=', 'rejected')
                    ->get();
                
                $similar['exact_phone'] = $phoneMatches;
            }
        }

        // Find similar titles (using LIKE for simple matching)
        if ($title && strlen($title) > 5) {
            $titleQuery = (clone $query)
                ->where('status', '!=', 'rejected')
                ->where(function($q) use ($title, $categoryId) {
                    // Clean title for searching
                    $words = array_filter(explode(' ', $title), fn($w) => strlen($w) > 3);
                    
                    if (count($words) >= 2) {
                        foreach (array_slice($words, 0, 3) as $word) {
                            $q->orWhere('title', 'like', '%' . $word . '%')
                              ->orWhere('title_bn', 'like', '%' . $word . '%');
                        }
                    }
                    
                    // Also match if category same
                    if ($categoryId) {
                        $q->orWhere(function($sub) use ($title, $categoryId) {
                            $sub->where('category_id', $categoryId)
                                ->where(function($inner) use ($title) {
                                    $inner->where('title', 'like', '%' . substr($title, 0, 20) . '%')
                                          ->orWhere('title_bn', 'like', '%' . substr($title, 0, 20) . '%');
                                });
                        });
                    }
                });
            
            $titleMatches = $titleQuery->limit(10)->get();
            
            // Filter to only include reasonably similar ones
            $similar['similar_title'] = $titleMatches->filter(function($listing) use ($title, $similar) {
                // Don't include if already in phone matches
                if ($similar['exact_phone']->contains('id', $listing->id)) {
                    return false;
                }
                
                // Calculate simple similarity
                $similarity = 0;
                similar_text(strtolower($title), strtolower($listing->title), $similarity);
                
                return $similarity > 50; // 50% similarity threshold
            })->values();
        }

        return $similar;
    }

    /**
     * Check if this listing has potential duplicates
     */
    public function getDuplicates()
    {
        return self::findSimilar($this->phone, $this->title, $this->category_id, $this->id);
    }
}
