<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'category', 'upazila']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_bn', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('upazila')) {
            $query->where('upazila_id', $request->upazila);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Moderators can only see listings from their upazila
        if (auth()->user()->isModerator() && auth()->user()->upazila_id) {
            $query->where('upazila_id', auth()->user()->upazila_id);
        }

        $listings = $query->latest()->paginate(15);
        $categories = Category::active()->ordered()->get();
        $upazilas = Upazila::active()->ordered()->get();

        return view('admin.listings.index', compact('listings', 'categories', 'upazilas'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $upazilas = Upazila::active()->ordered()->get();
        return view('admin.listings.form', compact('categories', 'upazilas'));
    }

    public function store(Request $request)
    {
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
            'youtube' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_embed' => 'nullable|string',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();

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

        if ($validated['status'] === 'approved') {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
        }

        Listing::create($validated);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing created successfully.');
    }

    public function show(Listing $listing)
    {
        $listing->load(['user', 'category', 'upazila', 'comments' => function ($q) {
            $q->with('user')->latest()->take(10);
        }]);
        return view('admin.listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        $categories = Category::active()->ordered()->get();
        $upazilas = Upazila::active()->ordered()->get();
        return view('admin.listings.form', compact('listing', 'categories', 'upazilas'));
    }

    public function update(Request $request, Listing $listing)
    {
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
            'youtube' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_embed' => 'nullable|string',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('listings', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = $listing->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('listings/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        if ($validated['status'] === 'approved' && $listing->status !== 'approved') {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
        }

        $listing->update($validated);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing updated successfully.');
    }

    public function destroy(Listing $listing)
    {
        $listing->comments()->delete();
        $listing->delete();
        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function approve(Listing $listing)
    {
        $listing->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Listing approved successfully.');
    }

    public function reject(Listing $listing)
    {
        $listing->update(['status' => 'rejected']);
        return back()->with('success', 'Listing rejected.');
    }

    public function toggleFeatured(Listing $listing)
    {
        $listing->update(['is_featured' => !$listing->is_featured]);
        return back()->with('success', 'Listing featured status updated.');
    }
}
