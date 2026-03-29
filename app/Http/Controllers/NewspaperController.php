<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\NewspaperEdition;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewspaperController extends Controller
{
    private function getNewspaperCategory()
    {
        return Category::where('slug', 'newspaper')->firstOrFail();
    }

    /**
     * List all newspapers
     */
    public function index(Request $request)
    {
        $category = $this->getNewspaperCategory();
        $upazilas = Upazila::active()->ordered()->get();

        $query = Listing::approved()
            ->where('category_id', $category->id)
            ->with(['upazila', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('upazila')) {
            $query->where('upazila_id', $request->upazila);
        }

        if ($request->filled('type')) {
            $type = $request->type;
            $query->where(function ($q) use ($type) {
                $q->whereJsonContains('extra_fields->newspaper_type', $type);
            });
        }

        $newspapers = $query->withCount('editions')
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(12);

        return view('frontend.newspapers.index', compact('newspapers', 'category', 'upazilas'));
    }

    /**
     * Show a newspaper with its editions
     */
    public function show(Listing $newspaper)
    {
        $category = $this->getNewspaperCategory();

        if ($newspaper->category_id !== $category->id) {
            abort(404);
        }

        $newspaper->increment('views');
        $newspaper->load(['upazila', 'user', 'comments']);

        $editions = NewspaperEdition::where('listing_id', $newspaper->id)
            ->active()
            ->orderByDesc('edition_date')
            ->paginate(15);

        $canUpload = false;
        if (Auth::check()) {
            $user = Auth::user();
            $canUpload = $user->isAdmin() || $user->isSuperAdmin() ||
                ($user->id === $newspaper->user_id && $user->isActive() && $user->hasApprovedCategoryAccess($category->id));
        }

        return view('frontend.newspapers.show', compact('newspaper', 'editions', 'canUpload', 'category'));
    }

    /**
     * View a specific edition (reader view)
     */
    public function readEdition(Listing $newspaper, NewspaperEdition $edition)
    {
        if ($edition->listing_id !== $newspaper->id) {
            abort(404);
        }

        $newspaper->load('upazila');

        return view('frontend.newspapers.read', compact('newspaper', 'edition'));
    }

    /**
     * Store a new edition (authenticated newspaper owners)
     */
    public function storeEdition(Request $request, Listing $newspaper)
    {
        $category = $this->getNewspaperCategory();

        if ($newspaper->category_id !== $category->id) {
            abort(404);
        }

        $user = Auth::user();
        $canUpload = $user->isAdmin() || $user->isSuperAdmin() ||
            ($user->id === $newspaper->user_id && $user->isActive() && $user->hasApprovedCategoryAccess($category->id));

        if (!$canUpload) {
            return back()->with('error', 'আপনার এই সংবাদপত্রে সংস্করণ আপলোড করার অনুমতি নেই।');
        }

        $request->validate([
            'edition_date' => 'required|date|unique:newspaper_editions,edition_date,NULL,id,listing_id,' . $newspaper->id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'pages' => 'required|array|min:1|max:30',
            'pages.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'pdf_file' => 'nullable|mimes:pdf|max:20480',
        ], [
            'edition_date.unique' => 'এই তারিখের সংস্করণ আগেই আপলোড করা হয়েছে।',
            'pages.required' => 'কমপক্ষে একটি পৃষ্ঠার ছবি আপলোড করুন।',
            'pages.*.max' => 'প্রতিটি ছবি সর্বোচ্চ ৫MB হতে পারে।',
            'pdf_file.max' => 'PDF ফাইল সর্বোচ্চ ২০MB হতে পারে।',
        ]);

        $pages = [];
        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $page) {
                $pages[] = $page->store('newspapers/' . $newspaper->id, 'public');
            }
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('newspapers/' . $newspaper->id . '/pdf', 'public');
        }

        NewspaperEdition::create([
            'listing_id' => $newspaper->id,
            'uploaded_by' => $user->id,
            'edition_date' => $request->edition_date,
            'title' => $request->title,
            'description' => $request->description,
            'pages' => $pages,
            'pdf_file' => $pdfPath,
        ]);

        return back()->with('success', 'সংস্করণ সফলভাবে আপলোড করা হয়েছে!');
    }

    /**
     * Delete an edition
     */
    public function deleteEdition(Listing $newspaper, NewspaperEdition $edition)
    {
        if ($edition->listing_id !== $newspaper->id) {
            abort(404);
        }

        $user = Auth::user();
        $canDelete = $user->isAdmin() || $user->isSuperAdmin() || $user->id === $newspaper->user_id;

        if (!$canDelete) {
            return back()->with('error', 'আপনার এই সংস্করণ মুছে ফেলার অনুমতি নেই।');
        }

        // Delete files
        if ($edition->pages) {
            foreach ($edition->pages as $page) {
                Storage::disk('public')->delete($page);
            }
        }
        if ($edition->pdf_file) {
            Storage::disk('public')->delete($edition->pdf_file);
        }

        $edition->delete();

        return back()->with('success', 'সংস্করণ মুছে ফেলা হয়েছে।');
    }
}
