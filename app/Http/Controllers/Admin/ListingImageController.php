<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingImageController extends Controller
{
    /**
     * Display all listing images for approval
     */
    public function index(Request $request)
    {
        $query = ListingImage::with(['listing', 'user']);

        // Filter by status - only apply filter if status has a value
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // If no status filter provided and no query params at all, default to pending
        // Otherwise show all statuses
        elseif (!$request->has('status') && !$request->has('type')) {
            $query->pending();
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by listing
        if ($request->filled('listing_id')) {
            $query->where('listing_id', $request->listing_id);
        }

        $images = $query->latest()->paginate(20);

        $stats = [
            'pending' => ListingImage::pending()->count(),
            'approved' => ListingImage::approved()->count(),
            'rejected' => ListingImage::where('status', 'rejected')->count(),
        ];

        $types = ListingImage::getTypes();
        $statuses = ListingImage::getStatuses();

        return view('admin.listing-images.index', compact('images', 'stats', 'types', 'statuses'));
    }

    /**
     * Show single image details
     */
    public function show(ListingImage $listingImage)
    {
        $listingImage->load(['listing', 'user']);
        return view('admin.listing-images.show', compact('listingImage'));
    }

    /**
     * Approve an image
     */
    public function approve(ListingImage $listingImage)
    {
        $listingImage->approve();

        return redirect()->back()->with('success', 'ছবিটি সফলভাবে অনুমোদিত হয়েছে।');
    }

    /**
     * Reject an image
     */
    public function reject(ListingImage $listingImage)
    {
        $listingImage->reject();

        return redirect()->back()->with('success', 'ছবিটি বাতিল করা হয়েছে।');
    }

    /**
     * Bulk approve images
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:listing_images,id',
        ]);

        ListingImage::whereIn('id', $validated['ids'])->update(['status' => 'approved']);

        return redirect()->back()->with('success', count($validated['ids']) . 'টি ছবি অনুমোদিত হয়েছে।');
    }

    /**
     * Bulk reject images
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:listing_images,id',
        ]);

        ListingImage::whereIn('id', $validated['ids'])->update(['status' => 'rejected']);

        return redirect()->back()->with('success', count($validated['ids']) . 'টি ছবি বাতিল হয়েছে।');
    }

    /**
     * Delete an image
     */
    public function destroy(ListingImage $listingImage)
    {
        // Delete file
        if ($listingImage->image && Storage::disk('public')->exists($listingImage->image)) {
            Storage::disk('public')->delete($listingImage->image);
        }

        $listingImage->delete();

        return redirect()->back()->with('success', 'ছবিটি মুছে ফেলা হয়েছে।');
    }
}
