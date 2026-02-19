<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ListingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'user_id',
        'image',
        'title',
        'description',
        'type',
        'position',
        'display_size',
        'status',
        'valid_from',
        'valid_until',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    // Image types
    const TYPE_OFFER = 'offer';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_BANNER = 'banner';
    const TYPE_GALLERY = 'gallery';
    const TYPE_MENU = 'menu';
    const TYPE_OTHER = 'other';

    // Position constants
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_CENTER = 'center';
    const POSITION_TOP_LEFT = 'top-left';
    const POSITION_TOP_RIGHT = 'top-right';
    const POSITION_BOTTOM_LEFT = 'bottom-left';
    const POSITION_BOTTOM_RIGHT = 'bottom-right';
    const POSITION_FULL_WIDTH = 'full-width';

    // Display size constants
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    const SIZE_EXTRA_LARGE = 'extra-large';
    const SIZE_FULL_WIDTH = 'full-width';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function getTypes(): array
    {
        return [
            self::TYPE_OFFER => __('Offer'),
            self::TYPE_PROMOTION => __('Promotion'),
            self::TYPE_BANNER => __('Banner'),
            self::TYPE_GALLERY => __('Gallery'),
            self::TYPE_MENU => __('Menu'),
            self::TYPE_OTHER => __('Other'),
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REJECTED => __('Rejected'),
        ];
    }

    public static function getPositions(): array
    {
        return [
            self::POSITION_LEFT => __('বামে (Left)'),
            self::POSITION_RIGHT => __('ডানে (Right)'),
            self::POSITION_CENTER => __('মাঝে (Center)'),
            self::POSITION_TOP_LEFT => __('উপরে বামে (Top Left)'),
            self::POSITION_TOP_RIGHT => __('উপরে ডানে (Top Right)'),
            self::POSITION_BOTTOM_LEFT => __('নিচে বামে (Bottom Left)'),
            self::POSITION_BOTTOM_RIGHT => __('নিচে ডানে (Bottom Right)'),
            self::POSITION_FULL_WIDTH => __('পূর্ণ প্রস্থ (Full Width)'),
        ];
    }

    public static function getDisplaySizes(): array
    {
        return [
            self::SIZE_SMALL => __('ছোট - Small (150x150)'),
            self::SIZE_MEDIUM => __('মাঝারি - Medium (300x300)'),
            self::SIZE_LARGE => __('বড় - Large (450x450)'),
            self::SIZE_EXTRA_LARGE => __('অতিরিক্ত বড় - Extra Large (600x600)'),
            self::SIZE_FULL_WIDTH => __('পূর্ণ প্রস্থ - Full Width (100%)'),
        ];
    }

    public function getDisplaySizeClass(): string
    {
        return match($this->display_size) {
            self::SIZE_SMALL => 'col-6 col-md-3',
            self::SIZE_MEDIUM => 'col-6 col-md-4',
            self::SIZE_LARGE => 'col-12 col-md-6',
            self::SIZE_EXTRA_LARGE => 'col-12 col-md-8',
            self::SIZE_FULL_WIDTH => 'col-12',
            default => 'col-6 col-md-4',
        };
    }

    public function getPositionClass(): string
    {
        return match($this->position) {
            self::POSITION_LEFT => 'text-start',
            self::POSITION_RIGHT => 'text-end',
            self::POSITION_CENTER => 'text-center mx-auto',
            self::POSITION_TOP_LEFT => 'text-start',
            self::POSITION_TOP_RIGHT => 'text-end',
            self::POSITION_BOTTOM_LEFT => 'text-start',
            self::POSITION_BOTTOM_RIGHT => 'text-end',
            self::POSITION_FULL_WIDTH => 'text-center',
            default => 'text-center',
        };
    }

    // Relationships
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', now());
        })->where(function ($q) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>=', now());
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return Storage::url($this->image);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => '<span class="badge bg-success">' . __('Approved') . '</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger">' . __('Rejected') . '</span>',
            default => '<span class="badge bg-warning">' . __('Pending') . '</span>',
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        $colors = [
            self::TYPE_OFFER => 'info',
            self::TYPE_PROMOTION => 'primary',
            self::TYPE_BANNER => 'success',
            self::TYPE_GALLERY => 'secondary',
            self::TYPE_MENU => 'warning',
            self::TYPE_OTHER => 'dark',
        ];
        
        $color = $colors[$this->type] ?? 'secondary';
        $label = self::getTypes()[$this->type] ?? $this->type;
        
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    // Methods
    public function isValid(): bool
    {
        if ($this->valid_from && $this->valid_from > now()) {
            return false;
        }
        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }
        return true;
    }

    public function approve(): void
    {
        $this->update(['status' => self::STATUS_APPROVED]);
    }

    public function reject(): void
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }
}
