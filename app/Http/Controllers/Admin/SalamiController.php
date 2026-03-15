<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalamiEntry;
use App\Models\SalamiSetting;
use Illuminate\Http\Request;

class SalamiController extends Controller
{
    /**
     * Show all salami entries for admin (grouped by user)
     */
    public function index(Request $request)
    {
        $query = SalamiEntry::selectRaw('phone, user_name, COUNT(*) as entries_count, SUM(amount) as total_amount, MAX(created_at) as last_entry')
            ->groupBy('phone', 'user_name');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderByDesc('last_entry')->paginate(30);
        
        // Statistics
        $stats = [
            'total_entries' => SalamiEntry::count(),
            'total_amount' => SalamiEntry::sum('amount'),
            'unique_users' => SalamiEntry::distinct('phone')->count('phone'),
            'today_entries' => SalamiEntry::whereDate('created_at', today())->count(),
            'today_amount' => SalamiEntry::whereDate('created_at', today())->sum('amount'),
        ];
        
        $settings = SalamiSetting::getSettings();
        
        return view('admin.salami.index', compact('users', 'stats', 'settings'));
    }

    /**
     * Get entries for a specific phone (JSON API)
     */
    public function getEntriesByPhone($phone)
    {
        $entries = SalamiEntry::where('phone', $phone)
            ->latest()
            ->get()
            ->map(function($entry) {
                return [
                    'id' => $entry->id,
                    'giver_name' => $entry->giver_name,
                    'giver_relation' => $entry->giver_relation,
                    'amount' => $entry->amount,
                    'note' => $entry->note,
                    'created_at' => $entry->created_at->format('d M, h:i A'),
                ];
            });
        
        return response()->json(['entries' => $entries]);
    }

    /**
     * Delete all entries by user phone
     */
    public function destroyUser($phone)
    {
        SalamiEntry::where('phone', $phone)->delete();
        
        return back()->with('success', 'ব্যবহারকারীর সকল এন্ট্রি মুছে ফেলা হয়েছে।');
    }

    /**
     * Show users grouped view
     */
    public function users()
    {
        $users = SalamiEntry::selectRaw('session_id, user_name, phone, COUNT(*) as entries_count, SUM(amount) as total_amount, MAX(created_at) as last_entry')
            ->groupBy('session_id', 'user_name', 'phone')
            ->orderByDesc('last_entry')
            ->paginate(30);
        
        return view('admin.salami.users', compact('users'));
    }

    /**
     * Show entries for a specific user
     */
    public function userEntries($sessionId)
    {
        $entries = SalamiEntry::where('session_id', $sessionId)->latest()->get();
        $user = $entries->first();
        $total = $entries->sum('amount');
        
        return view('admin.salami.user-entries', compact('entries', 'user', 'total'));
    }

    /**
     * Toggle enable/disable salami calculator
     */
    public function toggleStatus()
    {
        $settings = SalamiSetting::getSettings();
        $settings->is_enabled = !$settings->is_enabled;
        $settings->save();
        
        return back()->with('success', $settings->is_enabled ? 'সালামি ক্যালকুলেটর চালু হয়েছে।' : 'সালামি ক্যালকুলেটর বন্ধ হয়েছে।');
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
        
        $settings = SalamiSetting::getSettings();
        $settings->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }

    /**
     * Delete an entry
     */
    public function destroy($id)
    {
        $entry = SalamiEntry::findOrFail($id);
        $entry->delete();
        
        return back()->with('success', 'এন্ট্রি মুছে ফেলা হয়েছে।');
    }

    /**
     * Export data as CSV
     */
    public function export()
    {
        $entries = SalamiEntry::orderBy('user_name')->orderBy('created_at')->get();
        
        $csvData = "ব্যবহারকারী,মোবাইল,কার থেকে,সম্পর্ক,টাকা,নোট,তারিখ\n";
        
        foreach ($entries as $entry) {
            $csvData .= "\"{$entry->user_name}\",\"{$entry->phone}\",\"{$entry->giver_name}\",\"{$entry->giver_relation}\",{$entry->amount},\"{$entry->note}\",\"{$entry->created_at->format('d/m/Y H:i')}\"\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="salami-entries-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Clear all entries
     */
    public function clearAll()
    {
        SalamiEntry::truncate();
        
        return back()->with('success', 'সকল ডাটা মুছে ফেলা হয়েছে।');
    }
}
