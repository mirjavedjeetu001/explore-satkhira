<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Listing;
use App\Models\MpQuestion;
use App\Models\News;
use App\Models\Upazila;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'pending_users' => User::pending()->count(),
            'total_listings' => Listing::count(),
            'pending_listings' => Listing::pending()->count(),
            'approved_listings' => Listing::approved()->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::pending()->count(),
            'total_news' => News::count(),
            'unread_contacts' => Contact::unread()->count(),
            'pending_questions' => MpQuestion::pending()->count(),
            'total_upazilas' => Upazila::count(),
            'total_categories' => Category::count(),
        ];

        $recentListings = Listing::with(['user', 'category', 'upazila'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        $pendingQuestions = MpQuestion::with('user')
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        $recentContacts = Contact::latest()
            ->take(5)
            ->get();

        // User Leaderboard - Users with most listings
        $userLeaderboard = User::select('users.*')
            ->selectRaw('(SELECT COUNT(*) FROM listings WHERE listings.user_id = users.id) as total_listings')
            ->selectRaw('(SELECT COUNT(*) FROM listings WHERE listings.user_id = users.id AND listings.status = "approved") as approved_listings')
            ->selectRaw('(SELECT COUNT(*) FROM listings WHERE listings.user_id = users.id AND listings.status = "pending") as pending_listings')
            ->selectRaw('(SELECT COUNT(*) FROM listings WHERE listings.user_id = users.id AND listings.status = "rejected") as rejected_listings')
            ->having('total_listings', '>', 0)
            ->orderByDesc('total_listings')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentListings',
            'recentUsers',
            'pendingQuestions',
            'recentContacts',
            'userLeaderboard'
        ));
    }
}
