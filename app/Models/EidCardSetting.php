<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EidCardSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'title',
        'description',
    ];
    
    protected $casts = [
        'is_enabled' => 'boolean',
    ];
    
    /**
     * Get settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'is_enabled' => true,
                'title' => 'ঈদ গ্রিটিং কার্ড মেকার',
                'description' => 'আপনার ছবি দিয়ে সুন্দর ঈদ কার্ড তৈরি করুন!',
            ]);
        }
        
        return $settings;
    }
}
