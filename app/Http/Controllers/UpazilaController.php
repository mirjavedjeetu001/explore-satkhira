<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function index()
    {
        $upazilas = Upazila::active()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()])
            ->get();
        
        // Add count of "all upazila" listings to each upazila count
        $allUpazilaListingsCount = Listing::approved()->whereNull('upazila_id')->count();
        foreach ($upazilas as $upazila) {
            $upazila->listings_count += $allUpazilaListingsCount;
        }

        return view('frontend.upazilas.index', compact('upazilas'));
    }

    public function show(Upazila $upazila, Request $request)
    {
        $categories = Category::active()
            ->parentCategories()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()->where(function($query) use ($upazila) {
                $query->where('upazila_id', $upazila->id)->orWhereNull('upazila_id');
            })])
            ->get();

        // Include listings for this upazila OR listings with NULL upazila_id (all upazilas)
        $query = Listing::approved()
            ->where(function($q) use ($upazila) {
                $q->where('upazila_id', $upazila->id)->orWhereNull('upazila_id');
            })
            ->with(['category', 'user']);

        if ($request->filled('category')) {
            // Support both category ID and slug
            $categoryParam = $request->category;
            if (is_numeric($categoryParam)) {
                $query->where('category_id', $categoryParam);
            } else {
                // Find by slug
                $category = Category::where('slug', $categoryParam)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_bn', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $listings = $query->latest()->paginate(12);

        $featuredListings = Listing::approved()
            ->featured()
            ->where(function($q) use ($upazila) {
                $q->where('upazila_id', $upazila->id)->orWhereNull('upazila_id');
            })
            ->with(['category'])
            ->take(4)
            ->get();

        return view('frontend.upazilas.show', compact(
            'upazila',
            'categories',
            'listings',
            'featuredListings'
        ));
    }
}
