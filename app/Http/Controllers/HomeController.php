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
use App\Models\FuelStation;
use App\Models\TeamMember;
use App\Models\BloodDonor;
use App\Models\BloodSetting;
use App\Models\MangoSetting;
use App\Models\MangoStore;

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

        // Fuel availability reports - same logic as fuel page for full sync
        $fuelEnabled = FuelSetting::isEnabled();
        $fuelReports = collect();
        if ($fuelEnabled) {
            // Increment page views for fuel section
            FuelSetting::incrementPageViews();

            // Get all active stations with upazila and comment counts
            $fuelStations = FuelStation::with('upazila')
                ->withCount('comments')
                ->where('is_active', true)
                ->get();
            
            // Attach latest report for each station
            $fuelStations->each(function($station) {
                $station->displayReport = FuelReport::where('fuel_station_id', $station->id)
                    ->orderByDesc('created_at')
                    ->first();
            });
            
            // Filter out stations without reports, sort by latest update time
            $fuelStations = $fuelStations->filter(fn($s) => $s->displayReport !== null);
            
            $fuelStations = $fuelStations->sortBy(function($station) {
                return -$station->displayReport->created_at->timestamp;
            })->take(6)->values();
            
            // Map to reports with fuelStation attached for view compatibility
            $fuelReports = $fuelStations->map(function($station) {
                $report = $station->displayReport;
                $report->setRelation('fuelStation', $station);
                return $report;
            });
        }

        // Mango stores for homepage
        $mangoEnabled = MangoSetting::isEnabled();
        $mangoStores = collect();
        $mangoStoresTotal = 0;
        if ($mangoEnabled) {
            $mangoQuery = MangoStore::with(['upazila'])
                ->withCount(['products', 'ratings'])
                ->withAvg('ratings', 'rating')
                ->active();

            $mangoStoresTotal = $mangoQuery->count();

            $mangoStores = $mangoQuery
                ->orderByDesc('ratings_avg_rating')
                ->orderByDesc('ratings_count')
                ->latest()
                ->take(3)
                ->get();
        }

        // Team Members (only admins for homepage)
        $teamMembers = TeamMember::active()->ordered()->with('user')
            ->whereIn('website_role', ['super_admin', 'admin'])
            ->get();

        // Stats for counter section
        $totalListings = Listing::approved()->count();
        $totalVisitors = \App\Models\Visitor::count();

        // Blood top donors for homepage
        $bloodOnHomepage = BloodSetting::get('show_on_homepage', '0') === '1' && BloodSetting::isEnabled();
        $topBloodDonors = collect();
        if ($bloodOnHomepage) {
            $topBloodDonors = BloodDonor::active()
                ->individual()
                ->whereNull('parent_id')
                ->withCount('donationHistories')
                ->having('donation_histories_count', '>', 0)
                ->orderByDesc('donation_histories_count')
                ->latest()
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
            'fuelReports',
            'teamMembers',
            'totalListings',
            'totalVisitors',
            'bloodOnHomepage',
            'topBloodDonors',
            'mangoEnabled',
            'mangoStores',
            'mangoStoresTotal'
        ));
    }
}
