<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CelebrationCardSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'title',
        'description',
        'brand_name',
        'brand_tagline',
        'headline',
        'footer_text',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return self::first() ?? self::create([
            'is_enabled' => true,
            'title' => 'শুভেচ্ছা কার্ড মেকার',
            'description' => 'আপনার নাম ও পদবি দিয়ে সুন্দর একটি শুভেচ্ছা কার্ড তৈরি করুন এবং সামাজিক মাধ্যমে শেয়ার করুন।',
            'brand_name' => 'Explore Satkhira',
            'brand_tagline' => 'সবার গল্প, সবার পাশে',
            'headline' => 'দুর্গম যাত্রায় সকলকে অভিনন্দন',
            'footer_text' => 'এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে',
        ]);
    }

    public static function isEnabled(): bool
    {
        return (bool) (self::first()?->is_enabled ?? false);
    }
}
