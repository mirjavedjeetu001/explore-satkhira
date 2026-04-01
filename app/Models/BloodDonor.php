<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BloodDonor extends Model
{
    protected $fillable = [
        'name', 'phone', 'whatsapp_number', 'email', 'password',
        'blood_group', 'last_donation_date', 'is_available', 'hide_phone',
        'alternative_contact', 'type', 'organization_name', 'parent_id',
        'upazila_id', 'outside_area', 'address', 'available_areas', 'available_for',
        'not_reachable_count', 'status',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'hide_phone' => 'boolean',
        'available_areas' => 'array',
        'available_for' => 'array',
        'last_donation_date' => 'date',
    ];

    protected $hidden = ['password', 'remember_token'];

    // Relationships
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function comments()
    {
        return $this->hasMany(BloodComment::class);
    }

    public function donationHistories()
    {
        return $this->hasMany(BloodDonationHistory::class);
    }

    public function donors()
    {
        return $this->hasMany(BloodDonor::class, 'parent_id');
    }

    public function parentOrg()
    {
        return $this->belongsTo(BloodDonor::class, 'parent_id');
    }

    // Computed availability: manually available + cooldown passed + not too many reports
    public function getIsCurrentlyAvailableAttribute()
    {
        if (!$this->is_available || $this->status !== 'active') {
            return false;
        }

        $threshold = BloodSetting::notReachableThreshold();
        if ($this->not_reachable_count >= $threshold) {
            return false;
        }

        if ($this->last_donation_date) {
            $cooldown = BloodSetting::cooldownDays();
            return $this->last_donation_date->addDays($cooldown)->isPast();
        }

        return true;
    }

    public function getNextAvailableDateAttribute()
    {
        if (!$this->last_donation_date) {
            return null;
        }
        $cooldown = BloodSetting::cooldownDays();
        $nextDate = $this->last_donation_date->addDays($cooldown);
        return $nextDate->isFuture() ? $nextDate : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        $cooldown = BloodSetting::cooldownDays();
        $threshold = BloodSetting::notReachableThreshold();
        $cutoffDate = Carbon::now()->subDays($cooldown);

        return $query->active()
            ->where('is_available', true)
            ->where('not_reachable_count', '<', $threshold)
            ->where(function ($q) use ($cutoffDate) {
                $q->whereNull('last_donation_date')
                  ->orWhere('last_donation_date', '<=', $cutoffDate);
            });
    }

    public function scopeBloodGroup($query, $group)
    {
        return $query->where('blood_group', $group);
    }

    public function scopeInUpazila($query, $upazilaId)
    {
        return $query->where('upazila_id', $upazilaId);
    }

    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeOrganization($query)
    {
        return $query->where('type', 'organization');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('organization_name', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");
        });
    }
}
