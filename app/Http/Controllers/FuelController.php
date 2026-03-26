<?php

namespace App\Http\Controllers;

use App\Models\FuelStation;
use App\Models\FuelReport;
use App\Models\FuelSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FuelController extends Controller
{
    public function index(Request $request)
    {
        $upazilas = Upazila::orderBy('name')->get();
        $selectedUpazila = $request->get('upazila');
        
        // Get latest VERIFIED report for each station
        $stationsQuery = FuelStation::with(['upazila'])
            ->active()
            ->orderBy('name');
        
        if ($selectedUpazila) {
            $stationsQuery->where('upazila_id', $selectedUpazila);
        }
        
        $stations = $stationsQuery->get();
        
        // Attach latest verified report for each station
        $stations->each(function($station) {
            $station->displayReport = FuelReport::where('fuel_station_id', $station->id)
                ->where('is_verified', true)
                ->orderByDesc('created_at')
                ->first();
            
            // If no verified report, get latest unverified
            if (!$station->displayReport) {
                $station->displayReport = FuelReport::where('fuel_station_id', $station->id)
                    ->orderByDesc('created_at')
                    ->first();
            }
        });
        
        // Get total page views
        $totalViews = FuelStation::sum('view_count');
        
        // Get my reports using session
        $sessionId = $request->cookie('fuel_session_id');
        $myReports = [];
        if ($sessionId) {
            $myReports = FuelReport::where('session_id', $sessionId)
                ->with('fuelStation')
                ->orderByDesc('created_at')
                ->get();
        }
        
        return view('frontend.fuel.index', compact('upazilas', 'stations', 'selectedUpazila', 'myReports', 'totalViews'));
    }
    
    public function showStation($id)
    {
        $station = FuelStation::with(['upazila', 'reports' => function($q) {
            $q->orderByDesc('is_verified')->orderByDesc('created_at')->limit(10);
        }])->findOrFail($id);
        
        // Increment view count
        $station->increment('view_count');
        
        return view('frontend.fuel.station', compact('station'));
    }
    
    public function createReport($stationId = null)
    {
        $station = null;
        if ($stationId) {
            $station = FuelStation::with('upazila')->findOrFail($stationId);
        }
        $upazilas = Upazila::orderBy('name')->get();
        
        return view('frontend.fuel.create-report', compact('station', 'upazilas'));
    }
    
    public function storeReport(Request $request, $stationId = null)
    {
        $validated = $request->validate([
            'fuel_station_id' => 'required_unless:is_new_station,1|nullable|exists:fuel_stations,id',
            'is_new_station' => 'nullable|in:0,1',
            'new_station_name' => 'required_if:is_new_station,1|nullable|string|max:255',
            'new_station_upazila' => 'required_if:is_new_station,1|nullable|exists:upazilas,id',
            'new_station_address' => 'nullable|string|max:255',
            'new_station_map_link' => 'nullable|url|max:500',
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => 'required|string|max:20',
            'reporter_email' => 'nullable|email|max:100',
            'edit_pin' => 'required|digits:4',
            'petrol_available' => 'nullable|boolean',
            'diesel_available' => 'nullable|boolean',
            'octane_available' => 'nullable|boolean',
            'petrol_price' => 'nullable|numeric|min:0',
            'diesel_price' => 'nullable|numeric|min:0',
            'octane_price' => 'nullable|numeric|min:0',
            'queue_status' => 'required|in:none,short,medium,long',
            'notes' => 'nullable|string|max:500',
        ], [
            'fuel_station_id.required_without' => 'একটি পেট্রোল পাম্প নির্বাচন করুন অথবা নতুন পাম্প যুক্ত করুন।',
            'fuel_station_id.exists' => 'নির্বাচিত পাম্পটি সঠিক নয়।',
            'new_station_name.required_if' => 'নতুন পাম্পের নাম দিন।',
            'new_station_upazila.required_if' => 'উপজেলা নির্বাচন করুন।',
            'reporter_name.required' => 'আপনার নাম দিন।',
            'reporter_phone.required' => 'মোবাইল নম্বর দিন।',
            'edit_pin.required' => '৪ সংখ্যার PIN দিন।',
            'edit_pin.digits' => 'PIN অবশ্যই ৪ সংখ্যার হতে হবে।',
            'queue_status.required' => 'লাইনের অবস্থা নির্বাচন করুন।',
        ]);
        
        // Handle new station creation
        if ($request->is_new_station) {
            $station = FuelStation::create([
                'name' => $validated['new_station_name'],
                'upazila_id' => $validated['new_station_upazila'],
                'address' => $validated['new_station_address'] ?? null,
                'google_map_link' => $validated['new_station_map_link'] ?? null,
                'is_active' => true,
            ]);
            $fuelStationId = $station->id;
        } else {
            $fuelStationId = $stationId ?? $validated['fuel_station_id'];
        }
        
        // Get or create session ID
        $sessionId = $request->cookie('fuel_session_id');
        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
        }
        
        $report = FuelReport::create([
            'fuel_station_id' => $fuelStationId,
            'reporter_name' => $validated['reporter_name'],
            'reporter_phone' => $validated['reporter_phone'],
            'reporter_email' => $validated['reporter_email'] ?? null,
            'edit_pin' => $validated['edit_pin'],
            'session_id' => $sessionId,
            'petrol_available' => $request->boolean('petrol_available'),
            'diesel_available' => $request->boolean('diesel_available'),
            'octane_available' => $request->boolean('octane_available'),
            'petrol_price' => $validated['petrol_price'] ?? null,
            'diesel_price' => $validated['diesel_price'] ?? null,
            'octane_price' => $validated['octane_price'] ?? null,
            'queue_status' => $validated['queue_status'],
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('fuel.index')
            ->with('success', 'আপনার রিপোর্ট সফলভাবে জমা হয়েছে। আপনার PIN: ' . $validated['edit_pin'] . ' (এটি দিয়ে পরে এডিট করতে পারবেন)')
            ->cookie('fuel_session_id', $sessionId, 60 * 24 * 30); // 30 days
    }
    
    public function verifyPinForm($id)
    {
        $report = FuelReport::with('fuelStation')->findOrFail($id);
        return view('frontend.fuel.verify-pin', compact('report'));
    }
    
    public function verifyPin(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        
        $request->validate([
            'pin' => 'required|digits:4',
        ]);
        
        if ($report->edit_pin !== $request->pin) {
            return back()->with('error', 'ভুল PIN! আবার চেষ্টা করুন।');
        }
        
        // Store verified report ID in session
        session(['verified_report_' . $id => true]);
        
        return redirect()->route('fuel.edit-report', $id);
    }
    
    public function editReport($id)
    {
        $report = FuelReport::with('fuelStation.upazila')->findOrFail($id);
        
        // Check if user verified with PIN or owns via session
        $sessionId = request()->cookie('fuel_session_id');
        $isVerified = session('verified_report_' . $id);
        
        if (!$isVerified && $report->session_id !== $sessionId) {
            return redirect()->route('fuel.verify-pin', $id);
        }
        
        // Get the LATEST report for this station (not user's old report)
        $latestReport = FuelReport::where('fuel_station_id', $report->fuel_station_id)
            ->orderByDesc('created_at')
            ->first();
        
        return view('frontend.fuel.edit-report', [
            'report' => $report,
            'latestReport' => $latestReport,
            'station' => $report->fuelStation,
        ]);
    }
    
    public function updateReport(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        
        // Check if user verified with PIN or owns via session
        $sessionId = $request->cookie('fuel_session_id');
        $isVerified = session('verified_report_' . $id);
        
        if (!$isVerified && $report->session_id !== $sessionId) {
            return redirect()->route('fuel.verify-pin', $id);
        }
        
        $validated = $request->validate([
            'petrol_available' => 'nullable|boolean',
            'diesel_available' => 'nullable|boolean',
            'octane_available' => 'nullable|boolean',
            'petrol_price' => 'nullable|numeric|min:0',
            'diesel_price' => 'nullable|numeric|min:0',
            'octane_price' => 'nullable|numeric|min:0',
            'queue_status' => 'required|in:none,short,medium,long',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $report->update([
            'petrol_available' => $request->boolean('petrol_available'),
            'diesel_available' => $request->boolean('diesel_available'),
            'octane_available' => $request->boolean('octane_available'),
            'petrol_price' => $validated['petrol_price'] ?? null,
            'diesel_price' => $validated['diesel_price'] ?? null,
            'octane_price' => $validated['octane_price'] ?? null,
            'queue_status' => $validated['queue_status'],
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('fuel.index')
            ->with('success', 'রিপোর্ট সফলভাবে আপডেট হয়েছে।');
    }
    
    public function deleteReport(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        
        // Check if user verified with PIN or owns via session
        $sessionId = request()->cookie('fuel_session_id');
        $isVerified = session('verified_report_' . $id);
        
        // Also accept PIN in request for AJAX delete
        if ($request->has('pin') && $report->edit_pin === $request->pin) {
            $isVerified = true;
        }
        
        if (!$isVerified && $report->session_id !== $sessionId) {
            return response()->json(['error' => 'অননুমোদিত'], 403);
        }
        
        $report->delete();
        
        // Clear session verification
        session()->forget('verified_report_' . $id);
        
        return response()->json(['success' => true, 'message' => 'রিপোর্ট মুছে ফেলা হয়েছে।']);
    }
    
    // API for homepage widget
    public function getLatestReports(Request $request)
    {
        if (!FuelSetting::isEnabled()) {
            return response()->json(['enabled' => false]);
        }
        
        // Get latest report ID for each station (unique stations only)
        $latestReportIds = FuelReport::selectRaw('MAX(id) as id')
            ->groupBy('fuel_station_id')
            ->pluck('id');
        
        $reports = FuelReport::with(['fuelStation.upazila'])
            ->whereIn('id', $latestReportIds)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function($report) {
                return [
                    'id' => $report->id,
                    'station_name' => $report->fuelStation->name,
                    'upazila' => $report->fuelStation->upazila->name,
                    'petrol' => $report->petrol_available,
                    'diesel' => $report->diesel_available,
                    'octane' => $report->octane_available,
                    'petrol_price' => $report->petrol_price,
                    'diesel_price' => $report->diesel_price,
                    'octane_price' => $report->octane_price,
                    'queue' => $report->queue_status_bangla,
                    'time' => $report->created_at->diffForHumans(),
                ];
            });
        
        return response()->json([
            'enabled' => true,
            'title' => FuelSetting::get('section_title', 'জ্বালানি তেল আপডেট'),
            'subtitle' => FuelSetting::get('section_subtitle', 'সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা'),
            'reports' => $reports,
        ]);
    }
    
    // API to get station info with latest report
    public function getStationInfo($id)
    {
        $station = FuelStation::with('upazila')->find($id);
        
        if (!$station) {
            return response()->json(['error' => 'Station not found'], 404);
        }
        
        $latestReport = FuelReport::where('fuel_station_id', $id)
            ->orderByDesc('created_at')
            ->first();
        
        return response()->json([
            'station' => [
                'id' => $station->id,
                'name' => $station->name,
                'address' => $station->address,
                'google_map_link' => $station->google_map_link,
                'upazila' => $station->upazila->name,
            ],
            'latest_report' => $latestReport ? [
                'petrol_available' => $latestReport->petrol_available,
                'diesel_available' => $latestReport->diesel_available,
                'octane_available' => $latestReport->octane_available,
                'petrol_price' => $latestReport->petrol_price,
                'diesel_price' => $latestReport->diesel_price,
                'octane_price' => $latestReport->octane_price,
                'queue_status' => $latestReport->queue_status,
                'notes' => $latestReport->notes,
                'reporter_name' => $latestReport->reporter_name,
                'time' => $latestReport->created_at->diffForHumans(),
            ] : null,
        ]);
    }
    
    // Public vote on report correctness
    public function voteReport(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        $voteType = $request->input('vote'); // 'correct' or 'incorrect'
        
        // Check if already voted using cookie
        $votedReports = json_decode($request->cookie('fuel_voted_reports', '[]'), true);
        
        if (in_array($id, $votedReports)) {
            return response()->json([
                'success' => false,
                'message' => 'আপনি ইতিমধ্যে এই রিপোর্টে ভোট দিয়েছেন।'
            ]);
        }
        
        if ($voteType === 'correct') {
            $report->increment('correct_votes');
        } else {
            $report->increment('incorrect_votes');
        }
        
        // Auto-verify if correct votes >= 3 and more than incorrect
        if ($report->correct_votes >= 3 && $report->correct_votes > $report->incorrect_votes) {
            $report->update(['is_verified' => true]);
        }
        
        // Mark as voted
        $votedReports[] = $id;
        
        return response()->json([
            'success' => true,
            'message' => $voteType === 'correct' ? 'ধন্যবাদ! আপনি "সঠিক" বলেছেন। ✓' : 'ধন্যবাদ! আপনি "ভুল" বলেছেন।',
            'correct_votes' => $report->correct_votes,
            'incorrect_votes' => $report->incorrect_votes,
            'is_verified' => $report->is_verified,
        ])->cookie('fuel_voted_reports', json_encode($votedReports), 60 * 24 * 30); // 30 days
    }
}
