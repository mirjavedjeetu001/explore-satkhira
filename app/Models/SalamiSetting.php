<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalamiSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enabled',
        'title',
        'description',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the current settings
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'is_enabled' => true,
            'title' => 'ঈদ সালামি ক্যালকুলেটর',
            'description' => 'আপনার ঈদের সালামি হিসাব রাখুন সহজেই!',
        ]);
    }

    /**
     * Check if salami calculator is enabled
     */
    public static function isEnabled()
    {
        $settings = self::first();
        return $settings ? $settings->is_enabled : false;
    }
}
