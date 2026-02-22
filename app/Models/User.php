<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'upazila_id',
        'phone',
        'nid_number',
        'avatar',
        'address',
        'bio',
        'registration_purpose',
        'wants_mp_questions',
        'comment_only',
        'is_upazila_moderator',
        'is_own_business_moderator',
        'can_upload_ads',
        'wants_upazila_moderator',
        'wants_own_business_moderator',
        'requested_categories',
        'status',
        'is_verified',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'wants_mp_questions' => 'boolean',
            'comment_only' => 'boolean',
            'is_upazila_moderator' => 'boolean',
            'is_own_business_moderator' => 'boolean',
            'can_upload_ads' => 'boolean',
            'wants_upazila_moderator' => 'boolean',
            'wants_own_business_moderator' => 'boolean',
            'approved_at' => 'datetime',
            'requested_categories' => 'array',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function categoryPermissions()
    {
        return $this->belongsToMany(Category::class, 'user_category_permissions')
            ->withPivot('is_approved', 'approved_by', 'approved_at')
            ->withTimestamps();
    }

    public function approvedCategories()
    {
        return $this->belongsToMany(Category::class, 'user_category_permissions')
            ->wherePivot('is_approved', true)
            ->withTimestamps();
    }

    public function hasApprovedCategoryAccess(int $categoryId): bool
    {
        return $this->approvedCategories()->where('categories.id', $categoryId)->exists();
    }

    // Multiple upazilas relationship
    public function assignedUpazilas()
    {
        return $this->belongsToMany(Upazila::class, 'user_upazilas')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function activeUpazilas()
    {
        return $this->belongsToMany(Upazila::class, 'user_upazilas')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    public function hasUpazilaAccess(int $upazilaId): bool
    {
        // Admins have access to all upazilas
        if ($this->isAdmin()) {
            return true;
        }
        return $this->activeUpazilas()->where('upazilas.id', $upazilaId)->exists();
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function mpQuestions(): HasMany
    {
        return $this->hasMany(MpQuestion::class);
    }

    public function teamMember()
    {
        return $this->hasOne(TeamMember::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'super-admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role?->slug, ['super-admin', 'admin']);
    }

    public function isModerator(): bool
    {
        return $this->role?->slug === 'moderator';
    }

    public function isUser(): bool
    {
        return $this->role?->slug === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->slug === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->slug, $roles);
    }

    public function canManageUpazila(int $upazilaId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check assigned upazilas first (new system)
        if ($this->activeUpazilas()->where('upazilas.id', $upazilaId)->exists()) {
            return true;
        }

        // Fallback to old single upazila_id for backward compatibility
        return $this->isModerator() && $this->upazila_id === $upazilaId;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByRole($query, string $roleSlug)
    {
        return $query->whereHas('role', fn($q) => $q->where('slug', $roleSlug));
    }
}

