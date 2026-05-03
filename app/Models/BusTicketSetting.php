<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTicketSetting extends Model
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
            'title' => 'বাস টিকেট বেচাকেনা',
            'description' => 'ঈদসহ বিভিন্ন সময়ে অতিরিক্ত বাস টিকেট কিনে থাকলে বা কিনতে চাইলে এখানে বিজ্ঞাপন দিতে পারেন।',
        ]);
    }

    public static function isEnabled(): bool
    {
        $settings = self::first();
        return $settings ? $settings->is_enabled : false;
    }
}
