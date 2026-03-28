<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

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

    public static function incrementPageViews()
    {
        $updated = static::where('key', 'fuel_page_views')
            ->update(['value' => \DB::raw('CAST(value AS UNSIGNED) + 1')]);
        
        if (!$updated) {
            static::create(['key' => 'fuel_page_views', 'value' => '1']);
        }
    }
}
