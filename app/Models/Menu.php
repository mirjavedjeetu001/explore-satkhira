<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function parentItems(): HasMany
    {
        return $this->items()->whereNull('parent_id')->orderBy('sort_order');
    }

    public static function getByLocation(string $location)
    {
        return Cache::remember("menu_{$location}", 3600, function () use ($location) {
            return static::where('location', $location)
                ->where('is_active', true)
                ->with(['parentItems.children' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                }])
                ->first();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('menu_header');
        Cache::forget('menu_footer');
    }
}
