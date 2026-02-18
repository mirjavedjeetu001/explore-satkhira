<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Upazila;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->parentCategories()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()])
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    public function show(Category $category, Request $request)
    {
        $upazilas = Upazila::active()
            ->ordered()
            ->withCount(['listings' => fn($q) => $q->approved()->where('category_id', $category->id)])
            ->get();

        $query = Listing::approved()
            ->where('category_id', $category->id)
            ->with(['upazila', 'user']);

        if ($request->filled('upazila')) {
            $query->where('upazila_id', $request->upazila);
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

        return view('frontend.categories.show', compact(
            'category',
            'upazilas',
            'listings'
        ));
    }
}
