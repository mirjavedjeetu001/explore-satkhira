<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

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
        'template_image_path',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function getSettings(): self
    {
        $defaults = [
            'is_enabled' => true,
            'title' => 'শুভেচ্ছা কার্ড মেকার',
            'description' => 'আপনার নাম ও পদবি দিয়ে সুন্দর একটি শুভেচ্ছা কার্ড তৈরি করুন এবং সামাজিক মাধ্যমে শেয়ার করুন।',
            'brand_name' => 'Explore Satkhira',
            'brand_tagline' => 'সবার গল্প, সবার পাশে',
            'headline' => 'দুর্গম যাত্রায় সকলকে অভিনন্দন',
            'footer_text' => 'এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে',
        ];

        try {
            // Allow the app to render safely before the feature migration runs.
            if (! Schema::hasTable('celebration_card_settings')) {
                return new self(array_merge($defaults, ['is_enabled' => false]));
            }

            return self::first() ?? self::create($defaults);
        } catch (Throwable) {
            return new self(array_merge($defaults, ['is_enabled' => false]));
        }
    }

    public static function isEnabled(): bool
    {
        try {
            return (bool) (self::query()->value('is_enabled') ?? false);
        } catch (Throwable) {
            return false;
        }
    }
}
