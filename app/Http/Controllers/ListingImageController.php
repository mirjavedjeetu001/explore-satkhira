<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingImageController extends Controller
{
    /**
     * Display images for a listing
     */
    public function index(Listing $listing)
    {
        // Ensure user owns the listing
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $images = $listing->images()->paginate(12);

        return view('frontend.dashboard.listings.images.index', compact('listing', 'images'));
    }

    /**
     * Show form to upload images
     */
    public function create(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow image upload for approved listings
        if ($listing->status !== 'approved') {
            return redirect()->route('dashboard.listings')
                ->with('error', 'শুধুমাত্র অনুমোদিত তথ্যের জন্য ছবি আপলোড করা যাবে।');
        }

        $types = ListingImage::getTypes();
        $positions = ListingImage::getPositions();
        $sizes = ListingImage::getDisplaySizes();

        return view('frontend.dashboard.listings.images.create', compact('listing', 'types', 'positions', 'sizes'));
    }

    /**
     * Store new images
     */
    public function store(Request $request, Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($listing->status !== 'approved') {
            return redirect()->route('dashboard.listings')
                ->with('error', 'শুধুমাত্র অনুমোদিত তথ্যের জন্য ছবি আপলোড করা যাবে।');
        }

        $validated = $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|in:offer,promotion,banner,gallery,menu,other',
            'position' => 'required|in:left,right,center,top-left,top-right,bottom-left,bottom-right,full-width',
            'display_size' => 'required|in:small,medium,large,extra-large,full-width',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $uploadedCount = 0;

        foreach ($request->file('images') as $image) {
            $path = $image->store('listing-images/' . $listing->id, 'public');

            ListingImage::create([
                'listing_id' => $listing->id,
                'user_id' => auth()->id(),
                'image' => $path,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'position' => $validated['position'],
                'display_size' => $validated['display_size'],
                'valid_from' => $validated['valid_from'] ?? null,
                'valid_until' => $validated['valid_until'] ?? null,
                'status' => 'pending', // Needs admin approval
            ]);

            $uploadedCount++;
        }

        return redirect()->route('dashboard.listings.images', $listing)
            ->with('success', "{$uploadedCount}টি ছবি সফলভাবে আপলোড হয়েছে। অনুমোদনের অপেক্ষায় রয়েছে।");
    }

    /**
     * Show edit form
     */
    public function edit(Listing $listing, ListingImage $image)
    {
        if ($listing->user_id !== auth()->id() || $image->listing_id !== $listing->id) {
            abort(403, 'Unauthorized');
        }

        $types = ListingImage::getTypes();
        $positions = ListingImage::getPositions();
        $sizes = ListingImage::getDisplaySizes();

        return view('frontend.dashboard.listings.images.edit', compact('listing', 'image', 'types', 'positions', 'sizes'));
    }

    /**
     * Update image details
     */
    public function update(Request $request, Listing $listing, ListingImage $image)
    {
        if ($listing->user_id !== auth()->id() || $image->listing_id !== $listing->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'type' => 'required|in:offer,promotion,banner,gallery,menu,other',
            'position' => 'required|in:left,right,center,top-left,top-right,bottom-left,bottom-right,full-width',
            'display_size' => 'required|in:small,medium,large,extra-large,full-width',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        // If editing, set status back to pending for re-approval
        $validated['status'] = 'pending';

        $image->update($validated);

        return redirect()->route('dashboard.listings.images', $listing)
            ->with('success', 'তথ্য সফলভাবে আপডেট হয়েছে। পুনরায় অনুমোদনের অপেক্ষায় রয়েছে।');
    }

    /**
     * Delete an image
     */
    public function destroy(Listing $listing, ListingImage $image)
    {
        if ($listing->user_id !== auth()->id() || $image->listing_id !== $listing->id) {
            abort(403, 'Unauthorized');
        }

        // Delete the file
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return redirect()->route('dashboard.listings.images', $listing)
            ->with('success', 'ছবি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
