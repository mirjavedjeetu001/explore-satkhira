<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonationHistory extends Model
{
    protected $fillable = ['blood_donor_id', 'donation_date', 'note'];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(BloodDonor::class, 'blood_donor_id');
    }
}
