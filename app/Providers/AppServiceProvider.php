<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 pagination
        Paginator::useBootstrapFive();
        
        // Share site settings globally with all views
        View::composer('*', function ($view) {
            $siteSettings = cache()->rememberForever('site_settings_all', function () {
                try {
                    return SiteSetting::pluck('value', 'key')->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            });
            $view->with('siteSettings', $siteSettings);
        });
    }
}
