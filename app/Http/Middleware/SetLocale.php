<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is set in session
        $locale = Session::get('locale', config('app.locale'));
        
        // Validate locale
        if (!in_array($locale, ['bn', 'en'])) {
            $locale = 'bn';
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
