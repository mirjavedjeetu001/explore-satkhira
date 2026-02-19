<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    protected $fillable = [
        'user_id',
        'website_role',
        'website_role_bn',
        'designation',
        'designation_bn',
        'bio',
        'bio_bn',
        'phone',
        'email',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Predefined website roles
    public static function getWebsiteRoles(): array
    {
        return [
            'web_developer' => 'Web Developer',
            'admin' => 'Admin',
            'super_admin' => 'Super Admin',
            'district_moderator' => 'District Moderator',
            'upazila_moderator' => 'Upazila Moderator',
            'content_manager' => 'Content Manager',
            'editor' => 'Editor',
            'contributor' => 'Contributor',
        ];
    }

    public static function getWebsiteRolesBn(): array
    {
        return [
            'web_developer' => 'ওয়েব ডেভেলপার',
            'admin' => 'অ্যাডমিন',
            'super_admin' => 'সুপার অ্যাডমিন',
            'district_moderator' => 'জেলা মডারেটর',
            'upazila_moderator' => 'উপজেলা মডারেটর',
            'content_manager' => 'কন্টেন্ট ম্যানেজার',
            'editor' => 'সম্পাদক',
            'contributor' => 'কন্ট্রিবিউটর',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'asc');
    }

    // Get role display name based on locale
    public function getRoleDisplayAttribute(): string
    {
        if (app()->getLocale() === 'bn') {
            return $this->website_role_bn ?: self::getWebsiteRolesBn()[$this->website_role] ?? $this->website_role;
        }
        return self::getWebsiteRoles()[$this->website_role] ?? $this->website_role;
    }

    // Get designation based on locale
    public function getDesignationDisplayAttribute(): string
    {
        if (app()->getLocale() === 'bn') {
            return $this->designation_bn ?: $this->designation ?: '';
        }
        return $this->designation ?: '';
    }

    // Get bio based on locale
    public function getBioDisplayAttribute(): string
    {
        if (app()->getLocale() === 'bn') {
            return $this->bio_bn ?: $this->bio ?: '';
        }
        return $this->bio ?: '';
    }
}
