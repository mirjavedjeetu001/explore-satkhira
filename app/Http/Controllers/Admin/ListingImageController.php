<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingImage;
use App\Mail\ListingImageApprovedMail;
use App\Mail\ListingImageRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $listingImage->update([
            'status' => ListingImage::STATUS_APPROVED,
            'rejection_reason' => null,
        ]);

        // Send approval email
        if ($listingImage->user && $listingImage->user->email) {
            try {
                Mail::to($listingImage->user->email)->send(new ListingImageApprovedMail($listingImage));
            } catch (\Exception $e) {
                \Log::error('Failed to send listing image approval email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'ছবিটি সফলভাবে অনুমোদিত হয়েছে এবং ইমেইল পাঠানো হয়েছে।');
    }

    /**
     * Reject an image
     */
    public function reject(Request $request, ListingImage $listingImage)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $listingImage->update([
            'status' => ListingImage::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send rejection email
        if ($listingImage->user && $listingImage->user->email) {
            try {
                Mail::to($listingImage->user->email)->send(new ListingImageRejectedMail($listingImage, $validated['rejection_reason']));
            } catch (\Exception $e) {
                \Log::error('Failed to send listing image rejection email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'ছবিটি বাতিল করা হয়েছে এবং ইমেইল পাঠানো হয়েছে।');
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

        $images = ListingImage::whereIn('id', $validated['ids'])->with('user')->get();
        
        foreach ($images as $image) {
            $image->update([
                'status' => ListingImage::STATUS_APPROVED,
                'rejection_reason' => null,
            ]);
            
            // Send approval email
            if ($image->user && $image->user->email) {
                try {
                    Mail::to($image->user->email)->send(new ListingImageApprovedMail($image));
                } catch (\Exception $e) {
                    \Log::error('Failed to send listing image approval email: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', count($validated['ids']) . 'টি ছবি অনুমোদিত হয়েছে এবং ইমেইল পাঠানো হয়েছে।');
    }

    /**
     * Bulk reject images
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:listing_images,id',
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $images = ListingImage::whereIn('id', $validated['ids'])->with('user')->get();
        
        foreach ($images as $image) {
            $image->update([
                'status' => ListingImage::STATUS_REJECTED,
                'rejection_reason' => $validated['rejection_reason'],
            ]);
            
            // Send rejection email
            if ($image->user && $image->user->email) {
                try {
                    Mail::to($image->user->email)->send(new ListingImageRejectedMail($image, $validated['rejection_reason']));
                } catch (\Exception $e) {
                    \Log::error('Failed to send listing image rejection email: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', count($validated['ids']) . 'টি ছবি বাতিল হয়েছে এবং ইমেইল পাঠানো হয়েছে।');
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

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Toggle homepage display
     */
    public function toggleHomepage(ListingImage $listingImage)
    {
        $listingImage->update([
            'show_on_homepage' => !$listingImage->show_on_homepage,
        ]);

        $status = $listingImage->show_on_homepage ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Homepage display {$status} for this ad.");
    }

    /**
     * Update homepage priority
     */
    public function updatePriority(Request $request, ListingImage $listingImage)
    {
        $validated = $request->validate([
            'priority' => 'required|integer|min:0|max:100',
        ]);

        $listingImage->update([
            'homepage_priority' => $validated['priority'],
        ]);

        return redirect()->back()->with('success', 'Homepage priority updated.');
    }
}
