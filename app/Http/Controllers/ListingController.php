<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Listing;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::approved()->with(['category', 'upazila', 'user']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('upazila')) {
            $query->where('upazila_id', $request->upazila);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_bn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $listings = $query->latest()->paginate(12);

        $categories = Category::active()->parentCategories()->ordered()->get();
        $upazilas = Upazila::active()->ordered()->get();

        return view('frontend.listings.index', compact('listings', 'categories', 'upazilas'));
    }

    public function show(Listing $listing)
    {
        if ($listing->status !== 'approved') {
            abort(404);
        }

        $listing->incrementViews();
        $listing->load(['category', 'upazila', 'user', 'approvedComments' => function ($q) {
            $q->parentComments()->with('replies')->latest();
        }]);

        $relatedListings = Listing::approved()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->take(4)
            ->get();

        return view('frontend.listings.show', compact('listing', 'relatedListings'));
    }

    public function storeComment(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'pending';

        $listing->comments()->create($validated);

        return back()->with('success', 'আপনার মন্তব্য সফলভাবে জমা দেওয়া হয়েছে। অনুমোদনের পর প্রদর্শিত হবে।');
    }

    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add a listing.');
        }

        if (!auth()->user()->isActive()) {
            return redirect()->route('dashboard')->with('error', 'Your account is not active yet.');
        }

        $categories = Category::active()->where('allow_user_submission', true)->ordered()->get();
        $upazilas = Upazila::active()->ordered()->get();

        return view('frontend.listings.create', compact('categories', 'upazilas'));
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isActive()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_embed' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = 'pending';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('listings', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('listings/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        Listing::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'আপনার লিস্টিং সফলভাবে জমা দেওয়া হয়েছে। অনুমোদনের জন্য অপেক্ষা করুন।');
    }
}
