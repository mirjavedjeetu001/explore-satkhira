<?php

namespace App\Http\Controllers;

use App\Models\SalamiEntry;
use App\Models\SalamiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SalamiController extends Controller
{
    /**
     * Show the salami calculator page
     */
    public function index()
    {
        $settings = SalamiSetting::getSettings();
        
        if (!$settings->is_enabled) {
            return redirect('/')->with('error', 'সালামি ক্যালকুলেটর বর্তমানে বন্ধ আছে।');
        }

        // Check if user has saved phone in cookie
        $savedPhone = request()->cookie('salami_phone');
        $savedName = request()->cookie('salami_name');
        
        $entries = collect();
        $total = 0;
        $hasSession = false;
        
        if ($savedPhone) {
            // Load existing entries for this phone number
            $entries = SalamiEntry::where('phone', $savedPhone)->latest()->get();
            $total = $entries->sum('amount');
            $hasSession = true;
        }

        return view('frontend.salami.index', compact('settings', 'entries', 'total', 'savedPhone', 'savedName', 'hasSession'));
    }

    /**
     * Start a new salami session or load existing
     */
    public function startSession(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone); // Clean phone number
        
        // Check if this phone has existing entries
        $existingEntries = SalamiEntry::where('phone', $phone)->latest()->get();
        $total = $existingEntries->sum('amount');
        
        // Create response with cookies (30 days expiry)
        $response = response()->json([
            'success' => true,
            'message' => $existingEntries->count() > 0 
                ? 'আপনার আগের ডাটা লোড হয়েছে!' 
                : 'সেশন শুরু হয়েছে!',
            'entries' => $existingEntries,
            'total' => $total,
            'total_formatted' => '৳' . number_format($total, 0),
            'has_previous_data' => $existingEntries->count() > 0
        ]);
        
        // Set cookies for 30 days
        return $response
            ->cookie('salami_phone', $phone, 60 * 24 * 30)
            ->cookie('salami_name', $request->user_name, 60 * 24 * 30);
    }

    /**
     * Add a new salami entry
     */
    public function addEntry(Request $request)
    {
        $request->validate([
            'giver_name' => 'required|string|max:100',
            'giver_relation' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:1|max:999999',
            'note' => 'nullable|string|max:255',
        ]);

        $phone = request()->cookie('salami_phone');
        $userName = request()->cookie('salami_name');
        
        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে প্রথমে আপনার তথ্য দিন।'
            ], 400);
        }

        $entry = SalamiEntry::create([
            'session_id' => $phone, // Using phone as session ID
            'user_name' => $userName ?? 'অজানা',
            'phone' => $phone,
            'giver_name' => $request->giver_name,
            'giver_relation' => $request->giver_relation,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        $total = SalamiEntry::where('phone', $phone)->sum('amount');
        $entriesCount = SalamiEntry::where('phone', $phone)->count();

        return response()->json([
            'success' => true,
            'entry' => $entry,
            'total' => $total,
            'total_formatted' => '৳' . number_format($total, 0),
            'entries_count' => $entriesCount,
            'message' => 'সালামি যোগ হয়েছে!'
        ]);
    }

    /**
     * Delete an entry
     */
    public function deleteEntry($id)
    {
        $phone = request()->cookie('salami_phone');
        $entry = SalamiEntry::where('id', $id)->where('phone', $phone)->first();
        
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'এন্ট্রি পাওয়া যায়নি।'
            ], 404);
        }

        $entry->delete();
        $total = SalamiEntry::where('phone', $phone)->sum('amount');

        return response()->json([
            'success' => true,
            'total' => $total,
            'total_formatted' => '৳' . number_format($total, 0),
            'message' => 'এন্ট্রি মুছে ফেলা হয়েছে!'
        ]);
    }

    /**
     * Get entries for current user
     */
    public function getEntries()
    {
        $phone = request()->cookie('salami_phone');
        if (!$phone) {
            return response()->json([
                'entries' => [],
                'total' => 0,
                'total_formatted' => '৳0'
            ]);
        }

        $entries = SalamiEntry::where('phone', $phone)->latest()->get();
        $total = $entries->sum('amount');

        return response()->json([
            'entries' => $entries,
            'total' => $total,
            'total_formatted' => '৳' . number_format($total, 0),
        ]);
    }

    /**
     * Logout - clear cookies and start fresh
     */
    public function resetSession()
    {
        $response = response()->json([
            'success' => true,
            'message' => 'লগআউট হয়েছে!'
        ]);
        
        // Clear cookies
        return $response
            ->cookie('salami_phone', '', -1)
            ->cookie('salami_name', '', -1);
    }
}
