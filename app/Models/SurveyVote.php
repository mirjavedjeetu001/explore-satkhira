<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyVote extends Model
{
    protected $fillable = [
        'survey_id', 'name', 'phone', 'class_type', 'department',
        'year', 'session', 'selected_option', 'comment',
        'device_fingerprint', 'ip_address', 'is_cancelled',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
