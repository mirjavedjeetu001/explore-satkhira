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

        return view('frontend.upazilas.index', compact('upazilas'));
    }

    public function show(Upazila $upazila, Request $request)
    {
        $categories = Category::active()
            ->parentCategories()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()->where('upazila_id', $upazila->id)])
            ->get();

        $query = Listing::approved()
            ->where('upazila_id', $upazila->id)
            ->with(['category', 'user']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
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
            ->where('upazila_id', $upazila->id)
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
