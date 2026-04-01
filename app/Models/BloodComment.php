<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodComment extends Model
{
    protected $fillable = ['blood_donor_id', 'name', 'phone', 'comment', 'status'];

    public function donor()
    {
        return $this->belongsTo(BloodDonor::class, 'blood_donor_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
