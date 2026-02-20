<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Upazila;
use App\Models\News;
use App\Models\Page;
use App\Models\MpProfile;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml .= '<sitemap><loc>' . url('/sitemap-listings.xml') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod></sitemap>';
        $xml .= '<sitemap><loc>' . url('/sitemap-categories.xml') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod></sitemap>';
        $xml .= '<sitemap><loc>' . url('/sitemap-upazilas.xml') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod></sitemap>';
        $xml .= '<sitemap><loc>' . url('/sitemap-pages.xml') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod></sitemap>';
        $xml .= '</sitemapindex>';
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function listings()
    {
        $listings = Listing::where('status', 'approved')
            ->select('id', 'slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($listings as $listing) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/listing/' . ($listing->slug ?? $listing->id)) . '</loc>';
            $xml .= '<lastmod>' . $listing->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function categories()
    {
        $categories = Category::where('is_active', true)
            ->select('id', 'slug', 'updated_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($categories as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/category/' . ($category->slug ?? $category->id)) . '</loc>';
            $xml .= '<lastmod>' . ($category->updated_at ? $category->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function upazilas()
    {
        $upazilas = Upazila::select('id', 'slug', 'updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($upazilas as $upazila) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/upazila/' . ($upazila->slug ?? $upazila->id)) . '</loc>';
            $xml .= '<lastmod>' . ($upazila->updated_at ? $upazila->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function pages()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Main pages
        $xml .= '<url><loc>' . url('/') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>';
        $xml .= '<url><loc>' . url('/listings') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>';
        $xml .= '<url><loc>' . url('/about') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        $xml .= '<url><loc>' . url('/contact') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        $xml .= '<url><loc>' . url('/mp') . '</loc><lastmod>' . now()->toAtomString() . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
        
        // Static pages
        $pages = Page::where('is_published', true)->select('id', 'slug', 'updated_at')->get();
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/page/' . ($page->slug ?? $page->id)) . '</loc>';
            $xml .= '<lastmod>' . ($page->updated_at ? $page->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }
        
        // News
        $news = News::where('is_published', true)->select('id', 'slug', 'updated_at')->orderBy('updated_at', 'desc')->get();
        foreach ($news as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/news/' . ($item->slug ?? $item->id)) . '</loc>';
            $xml .= '<lastmod>' . ($item->updated_at ? $item->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }
        
        // MP Profiles
        $mpProfiles = MpProfile::select('id', 'slug', 'updated_at')->get();
        foreach ($mpProfiles as $mp) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/mp/' . ($mp->slug ?? $mp->id)) . '</loc>';
            $xml .= '<lastmod>' . ($mp->updated_at ? $mp->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
