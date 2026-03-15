<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EidCard;
use App\Models\EidCardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EidCardController extends Controller
{
    /**
     * Show all eid cards (grouped by user)
     */
    public function index(Request $request)
    {
        $query = EidCard::selectRaw('phone, name, COUNT(*) as cards_count, MAX(created_at) as last_created')
            ->groupBy('phone', 'name');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderByDesc('last_created')->paginate(30);
        
        // Statistics
        $stats = [
            'total_cards' => EidCard::count(),
            'unique_users' => EidCard::distinct('phone')->count('phone'),
            'today_cards' => EidCard::whereDate('created_at', today())->count(),
        ];
        
        $settings = EidCardSetting::getSettings();
        
        return view('admin.eid-card.index', compact('users', 'stats', 'settings'));
    }
    
    /**
     * Get cards for a specific phone (JSON API)
     */
    public function getCardsByPhone($phone)
    {
        $cards = EidCard::where('phone', $phone)
            ->latest()
            ->get()
            ->map(function($card) {
                return [
                    'id' => $card->id,
                    'name' => $card->name,
                    'designation' => $card->designation,
                    'custom_message' => $card->custom_message,
                    'template' => $card->template,
                    'photo_url' => $card->photo ? asset('storage/' . $card->photo) : null,
                    'created_at' => $card->created_at->format('d M, h:i A'),
                ];
            });
        
        return response()->json(['success' => true, 'cards' => $cards]);
    }
    
    /**
     * Toggle enable/disable
     */
    public function toggleStatus()
    {
        $settings = EidCardSetting::getSettings();
        $settings->is_enabled = !$settings->is_enabled;
        $settings->save();
        
        return back()->with('success', $settings->is_enabled ? 'ঈদ কার্ড মেকার চালু হয়েছে।' : 'ঈদ কার্ড মেকার বন্ধ হয়েছে।');
    }
    
    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        
        $settings = EidCardSetting::getSettings();
        $settings->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }
    
    /**
     * Delete a card
     */
    public function destroy($id)
    {
        $card = EidCard::findOrFail($id);
        
        // Delete photo
        if ($card->photo) {
            Storage::disk('public')->delete($card->photo);
        }
        
        $card->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'কার্ড মুছে ফেলা হয়েছে।');
    }
    
    /**
     * Delete all cards by user
     */
    public function destroyUser($phone)
    {
        $cards = EidCard::where('phone', $phone)->get();
        
        foreach ($cards as $card) {
            if ($card->photo) {
                Storage::disk('public')->delete($card->photo);
            }
            $card->delete();
        }
        
        return back()->with('success', 'ব্যবহারকারীর সকল কার্ড মুছে ফেলা হয়েছে।');
    }
    
    /**
     * Clear all cards
     */
    public function clearAll()
    {
        // Delete all photos
        $cards = EidCard::whereNotNull('photo')->get();
        foreach ($cards as $card) {
            Storage::disk('public')->delete($card->photo);
        }
        
        EidCard::truncate();
        
        return back()->with('success', 'সকল কার্ড মুছে ফেলা হয়েছে।');
    }
}
