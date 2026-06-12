<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldCupSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function isEnabled()
    {
        return static::get('is_enabled', '1') === '1';
    }

    public static function showOnHomepage()
    {
        return static::get('show_on_homepage', '1') === '1';
    }

    public static function showFloatingButton()
    {
        return static::get('show_floating_button', '1') === '1';
    }
}
