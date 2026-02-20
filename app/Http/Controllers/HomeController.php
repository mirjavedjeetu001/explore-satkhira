<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\MpProfile;
use App\Models\News;
use App\Models\Slider;
use App\Models\Upazila;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->ordered()->get();
        
        $categories = Category::active()
            ->parentCategories()
            ->inMenu()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()])
            ->get();

        $upazilas = Upazila::active()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()])
            ->get();

        $featuredListings = Listing::approved()
            ->featured()
            ->with(['category', 'upazila'])
            ->latest()
            ->take(8)
            ->get();

        $latestListings = Listing::approved()
            ->with(['category', 'upazila'])
            ->latest()
            ->take(12)
            ->get();

        $latestNews = News::active()
            ->news()
            ->latest()
            ->take(4)
            ->get();

        $mpProfiles = MpProfile::active()->orderBy('constituency')->take(4)->get();

        return view('frontend.home', compact(
            'sliders',
            'categories',
            'upazilas',
            'featuredListings',
            'latestListings',
            'latestNews',
            'mpProfiles'
        ));
    }
}
