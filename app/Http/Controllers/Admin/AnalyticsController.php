<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Visitor Statistics
        $visitorStats = [
            'today' => Visitor::today()->count(),
            'this_week' => Visitor::thisWeek()->count(),
            'this_month' => Visitor::thisMonth()->count(),
            'total' => Visitor::count(),
        ];

        // User Registration Statistics
        $userStats = [
            'today' => User::whereDate('created_at', today())->count(),
            'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total' => User::count(),
            'approved' => User::active()->count(),
            'pending' => User::pending()->count(),
        ];

        // Listing Statistics
        $listingStats = [
            'today' => Listing::whereDate('created_at', today())->count(),
            'this_week' => Listing::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Listing::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total' => Listing::count(),
            'approved' => Listing::approved()->count(),
            'pending' => Listing::pending()->count(),
        ];

        // Login Statistics (based on last_login_at field if exists, otherwise use updated_at as proxy)
        $loginStats = [
            'today' => User::whereDate('updated_at', today())->active()->count(),
            'this_week' => User::whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->active()->count(),
        ];

        // Device Statistics
        $deviceStats = Visitor::select('device', DB::raw('count(*) as count'))
            ->groupBy('device')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'device')
            ->toArray();

        // Browser Statistics
        $browserStats = Visitor::select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'browser')
            ->toArray();

        // Platform Statistics
        $platformStats = Visitor::select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'platform')
            ->toArray();

        // Last 7 Days Visitor Trend
        $visitorTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $visitorTrend[] = [
                'date' => $date->format('d M'),
                'count' => Visitor::whereDate('visit_date', $date)->count(),
            ];
        }

        // Last 7 Days Registration Trend
        $registrationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $registrationTrend[] = [
                'date' => $date->format('d M'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        }

        // Last 7 Days Listing Trend
        $listingTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $listingTrend[] = [
                'date' => $date->format('d M'),
                'count' => Listing::whereDate('created_at', $date)->count(),
            ];
        }

        // Recent Visitors
        $recentVisitors = Visitor::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Top Pages
        $topPages = Visitor::select('page_url', DB::raw('count(*) as count'))
            ->groupBy('page_url')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'visitorStats',
            'userStats',
            'listingStats',
            'loginStats',
            'deviceStats',
            'browserStats',
            'platformStats',
            'visitorTrend',
            'registrationTrend',
            'listingTrend',
            'recentVisitors',
            'topPages'
        ));
    }
}
