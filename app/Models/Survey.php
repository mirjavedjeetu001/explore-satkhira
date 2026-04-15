<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Survey extends Model
{
    protected $fillable = [
        'title', 'question', 'options', 'image', 'start_time', 'end_time',
        'is_active', 'show_on_homepage', 'has_comment_option', 'form_fields',
    ];

    protected $casts = [
        'options' => 'array',
        'form_fields' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'has_comment_option' => 'boolean',
    ];

    public function votes()
    {
        return $this->hasMany(SurveyVote::class);
    }

    public function getIsLiveAttribute()
    {
        return $this->is_active && now()->between($this->start_time, $this->end_time);
    }

    public function getIsEndedAttribute()
    {
        return now()->greaterThan($this->end_time);
    }

    public function getIsUpcomingAttribute()
    {
        return $this->is_active && now()->lessThan($this->start_time);
    }

    public function getResultsAttribute()
    {
        $total = $this->votes()->count();
        $results = [];
        foreach ($this->options as $option) {
            $count = $this->votes()->where('selected_option', $option)->count();
            $results[] = [
                'option' => $option,
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ];
        }
        return $results;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLive($query)
    {
        return $query->where('is_active', true)
                     ->where('start_time', '<=', now())
                     ->where('end_time', '>=', now());
    }

    public static function hasActiveSurvey()
    {
        return static::active()
            ->where('show_on_homepage', true)
            ->where('end_time', '>=', now())
            ->exists();
    }

    public static function getActiveSurvey()
    {
        return static::active()
            ->where('show_on_homepage', true)
            ->where('end_time', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
