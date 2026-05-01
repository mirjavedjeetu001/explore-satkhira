<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'title',
        'description',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return self::first() ?? self::create([
            'is_enabled' => true,
            'title' => 'সাতক্ষীরার আম',
            'description' => 'সাতক্ষীরার সেরা আম সংগ্রহ করুন সরাসরি বাগান থেকে!',
        ]);
    }

    public static function isEnabled(): bool
    {
        $settings = self::first();
        return $settings ? $settings->is_enabled : false;
    }
}
