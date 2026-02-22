<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCanonicalDomain
{
    /**
     * Force redirect to canonical domain (exploresatkhira.com)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $canonicalDomain = 'exploresatkhira.com';
        $currentHost = $request->getHost();
        
        // If not on canonical domain, redirect
        if ($currentHost !== $canonicalDomain && $currentHost !== 'www.' . $canonicalDomain) {
            // Skip for localhost/development
            if (str_contains($currentHost, 'localhost') || str_contains($currentHost, '127.0.0.1')) {
                return $next($request);
            }
            
            $url = 'https://' . $canonicalDomain . $request->getRequestUri();
            return redirect()->away($url, 301);
        }
        
        return $next($request);
    }
}
