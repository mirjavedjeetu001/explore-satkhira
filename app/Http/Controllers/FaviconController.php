<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class FaviconController extends Controller
{
    public function svg()
    {
        // Get site name based on locale
        $locale = app()->getLocale();
        $siteName = $locale === 'bn' 
            ? (SiteSetting::get('site_name_bn') ?? 'এক্সপ্লোর সাতক্ষীরা')
            : (SiteSetting::get('site_name') ?? 'Explore Satkhira');
        
        // Get first character for the favicon
        $firstChar = mb_substr($siteName, 0, 1, 'UTF-8');
        
        // For Bangla "এক্সপ্লোর সাতক্ষীরা", use "সা" (for সাতক্ষীরা) 
        // For English "Explore Satkhira", use "S"
        if ($locale === 'bn') {
            // Find "সাতক্ষীরা" and use "সা"
            if (mb_strpos($siteName, 'সাতক্ষীরা', 0, 'UTF-8') !== false) {
                $firstChar = 'সা';
            } else {
                $firstChar = mb_substr($siteName, 0, 1, 'UTF-8');
            }
        } else {
            // For English, find "Satkhira" and use "S"
            if (stripos($siteName, 'Satkhira') !== false) {
                $firstChar = 'S';
            } else {
                $firstChar = strtoupper(substr($siteName, 0, 1));
            }
        }

        // Create a district-style location pin SVG favicon
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
    <defs>
        <linearGradient id="pinGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#28a745;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#20c997;stop-opacity:1" />
        </linearGradient>
        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="1" dy="2" stdDeviation="2" flood-color="rgba(0,0,0,0.3)"/>
        </filter>
    </defs>
    
    <!-- Location Pin Shape (District Marker) -->
    <path d="M32 4C20.954 4 12 12.954 12 24c0 15 20 36 20 36s20-21 20-36c0-11.046-8.954-20-20-20z" 
          fill="url(#pinGrad)" 
          filter="url(#shadow)"/>
    
    <!-- Inner Circle -->
    <circle cx="32" cy="24" r="14" fill="white"/>
    
    <!-- District Initial Text -->
    <text x="32" y="30" 
          font-family="Arial, 'Hind Siliguri', sans-serif" 
          font-size="16" 
          font-weight="bold" 
          fill="#28a745" 
          text-anchor="middle" 
          dominant-baseline="middle">{$firstChar}</text>
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours
    }

    public function png()
    {
        // If you want to serve a static PNG fallback
        $faviconPath = SiteSetting::get('site_favicon');
        
        if ($faviconPath && file_exists(storage_path('app/public/' . $faviconPath))) {
            return response()->file(storage_path('app/public/' . $faviconPath));
        }
        
        // Return a default PNG or redirect to SVG
        return redirect()->route('favicon.svg');
    }
}
