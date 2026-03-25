<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\MpProfile;
use App\Models\News;
use App\Models\Slider;
use App\Models\Upazila;
use App\Models\FuelReport;
use App\Models\FuelSetting;

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
            ->get()
            ->sortByDesc('listings_count')
            ->values();

        $upazilas = Upazila::active()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()])
            ->get();

        $featuredListings = Listing::approved()
            ->notExpired()
            ->featured()
            ->with(['category', 'upazila'])
            ->latest()
            ->take(8)
            ->get();

        $latestListings = Listing::approved()
            ->notExpired()
            ->with(['category', 'upazila'])
            ->latest()
            ->take(6)
            ->get();

        $latestNews = News::active()
            ->news()
            ->latest()
            ->take(4)
            ->get();

        $mpProfiles = MpProfile::active()->orderBy('constituency')->take(4)->get();

        // Homepage promotional ads
        $homepageAds = ListingImage::homepage()
            ->with('listing')
            ->take(6)
            ->get();

        // Fuel availability reports - latest report per station (unique stations only)
        $fuelEnabled = FuelSetting::isEnabled();
        $fuelReports = [];
        if ($fuelEnabled) {
            // Get latest report ID for each station
            $latestReportIds = FuelReport::selectRaw('MAX(id) as id')
                ->groupBy('fuel_station_id')
                ->pluck('id');
            
            $fuelReports = FuelReport::with(['fuelStation.upazila'])
                ->whereIn('id', $latestReportIds)
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        }

        return view('frontend.home', compact(
            'sliders',
            'categories',
            'upazilas',
            'featuredListings',
            'latestListings',
            'latestNews',
            'mpProfiles',
            'homepageAds',
            'fuelEnabled',
            'fuelReports'
        ));
    }
}
