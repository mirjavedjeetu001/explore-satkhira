<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function isEnabled()
    {
        return static::get('is_enabled', '1') === '1';
    }

    public static function cooldownDays()
    {
        return (int) static::get('cooldown_days', 90);
    }

    public static function notReachableThreshold()
    {
        return (int) static::get('not_reachable_threshold', 10);
    }
}
