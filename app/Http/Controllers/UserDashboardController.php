<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $recentListings = $user->listings()
            ->with(['category', 'upazila'])
            ->latest()
            ->take(5)
            ->get();

        $recentQuestions = $user->mpQuestions()
            ->with('mpProfile')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_listings' => $user->listings()->count(),
            'approved_listings' => $user->listings()->approved()->count(),
            'pending_listings' => $user->listings()->pending()->count(),
            'total_questions' => $user->mpQuestions()->count(),
        ];

        return view('frontend.dashboard.index', compact('user', 'recentListings', 'recentQuestions', 'stats'));
    }

    public function listings()
    {
        $listings = auth()->user()->listings()
            ->with(['category', 'upazila'])
            ->latest()
            ->paginate(10);

        return view('frontend.dashboard.listings', compact('listings'));
    }

    public function createListing()
    {
        $user = auth()->user();
        
        // Only Super Admin can see all categories and upazilas
        if ($user->isSuperAdmin()) {
            $categories = Category::active()->orderBy('name')->get();
            $upazilas = Upazila::active()->orderBy('name')->get();
        } else {
            // Filter categories based on user's approved categories
            $approvedCategoryIds = $user->approvedCategories()->pluck('categories.id');
            if ($approvedCategoryIds->isNotEmpty()) {
                $categories = Category::active()->whereIn('id', $approvedCategoryIds)->orderBy('name')->get();
            } else {
                $categories = collect(); // Empty collection
            }
            
            // Filter upazilas based on user's assigned upazilas
            $assignedUpazilaIds = $user->activeUpazilas()->pluck('upazilas.id');
            if ($assignedUpazilaIds->isNotEmpty()) {
                $upazilas = Upazila::active()->whereIn('id', $assignedUpazilaIds)->orderBy('name')->get();
            } else {
                // Fallback to old upazila_id field if no assigned upazilas
                if ($user->upazila_id) {
                    $upazilas = Upazila::where('id', $user->upazila_id)->get();
                } else {
                    $upazilas = collect(); // Empty collection
                }
            }
        }
        
        return view('frontend.dashboard.listings.create', compact('categories', 'upazilas'));
    }

    public function storeListing(Request $request)
    {
        $user = auth()->user();
        
        // If moderator with assigned upazila, force their upazila
        $upazilaRule = 'required|exists:upazilas,id';
        if ($user->isModerator() && $user->upazila_id) {
            $request->merge(['upazila_id' => $user->upazila_id]);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:2048',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = 'pending'; // Needs admin approval

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('listings', 'public');
        }

        Listing::create($validated);

        return redirect()->route('dashboard.listings')->with('success', 'তথ্য সফলভাবে জমা দেওয়া হয়েছে। অনুমোদনের পর প্রকাশিত হবে।');
    }

    public function editListing(Listing $listing)
    {
        $user = auth()->user();
        
        // Ensure user owns the listing
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        // Only Super Admin can see all categories and upazilas
        if ($user->isSuperAdmin()) {
            $categories = Category::active()->orderBy('name')->get();
            $upazilas = Upazila::active()->orderBy('name')->get();
        } else {
            // Filter categories based on user's approved categories
            $approvedCategoryIds = $user->approvedCategories()->pluck('categories.id');
            if ($approvedCategoryIds->isNotEmpty()) {
                $categories = Category::active()->whereIn('id', $approvedCategoryIds)->orderBy('name')->get();
            } else {
                $categories = collect(); // Empty collection
            }
            
            // Filter upazilas based on user's assigned upazilas
            $assignedUpazilaIds = $user->activeUpazilas()->pluck('upazilas.id');
            if ($assignedUpazilaIds->isNotEmpty()) {
                $upazilas = Upazila::active()->whereIn('id', $assignedUpazilaIds)->orderBy('name')->get();
            } else {
                // Fallback to old upazila_id field if no assigned upazilas
                if ($user->upazila_id) {
                    $upazilas = Upazila::where('id', $user->upazila_id)->get();
                } else {
                    $upazilas = collect(); // Empty collection
                }
            }
        }
        
        return view('frontend.dashboard.listings.edit', compact('listing', 'categories', 'upazilas'));
    }

    public function updateListing(Request $request, Listing $listing)
    {
        $user = auth()->user();
        
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        // If moderator with assigned upazila, force their upazila
        if ($user->isModerator() && $user->upazila_id) {
            $request->merge(['upazila_id' => $user->upazila_id]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:2048',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('listings', 'public');
        }

        $listing->update($validated);

        return redirect()->route('dashboard.listings')->with('success', 'তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function destroyListing(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $listing->delete();

        return redirect()->route('dashboard.listings')->with('success', 'তথ্য সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function questions()
    {
        $questions = auth()->user()->mpQuestions()
            ->with('mpProfile')
            ->latest()
            ->paginate(10);

        return view('frontend.dashboard.questions', compact('questions'));
    }

    public function profile()
    {
        $user = auth()->user()->load(['role', 'upazila']);
        return view('frontend.dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
