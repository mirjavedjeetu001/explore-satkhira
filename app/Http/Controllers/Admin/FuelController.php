<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelStation;
use App\Models\FuelReport;
use App\Models\FuelSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FuelController extends Controller
{
    // Stations Management
    public function stations()
    {
        $stations = FuelStation::with(['upazila', 'latestReport'])
            ->orderByDesc('created_at')
            ->paginate(20);
        
        return view('admin.fuel.stations', compact('stations'));
    }
    
    public function createStation()
    {
        $upazilas = Upazila::orderBy('name')->get();
        return view('admin.fuel.create-station', compact('upazilas'));
    }
    
    public function storeStation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'upazila_id' => 'required|exists:upazilas,id',
            'address' => 'nullable|string|max:255',
            'google_map_link' => 'nullable|url|max:500',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);
        
        FuelStation::create([
            'name' => $validated['name'],
            'upazila_id' => $validated['upazila_id'],
            'address' => $validated['address'] ?? null,
            'google_map_link' => $validated['google_map_link'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.fuel.stations')
            ->with('success', 'পেট্রোল পাম্প সফলভাবে যুক্ত হয়েছে।');
    }
    
    public function editStation($id)
    {
        $station = FuelStation::findOrFail($id);
        $upazilas = Upazila::orderBy('name')->get();
        return view('admin.fuel.edit-station', compact('station', 'upazilas'));
    }
    
    public function updateStation(Request $request, $id)
    {
        $station = FuelStation::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'upazila_id' => 'required|exists:upazilas,id',
            'address' => 'nullable|string|max:255',
            'google_map_link' => 'nullable|url|max:500',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);
        
        $station->update([
            'name' => $validated['name'],
            'upazila_id' => $validated['upazila_id'],
            'address' => $validated['address'] ?? null,
            'google_map_link' => $validated['google_map_link'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        
        return redirect()->route('admin.fuel.stations')
            ->with('success', 'পেট্রোল পাম্প সফলভাবে আপডেট হয়েছে।');
    }
    
    public function deleteStation($id)
    {
        $station = FuelStation::findOrFail($id);
        $station->delete();
        
        return response()->json(['success' => true, 'message' => 'পাম্প মুছে ফেলা হয়েছে।']);
    }
    
    public function toggleLock($id)
    {
        $station = FuelStation::findOrFail($id);
        $station->update(['is_locked' => !$station->is_locked]);
        
        return response()->json([
            'success' => true,
            'is_locked' => $station->is_locked,
            'message' => $station->is_locked ? 'পাম্প লক করা হয়েছে। এখন কেউ আপডেট দিতে পারবে না।' : 'পাম্প আনলক করা হয়েছে।',
        ]);
    }
    
    // Reports Management
    public function reports(Request $request)
    {
        // Check if we want all reports or just latest per station
        $showAll = $request->has('show_all') && $request->show_all == '1';
        
        if ($showAll) {
            // Show all reports (old behavior)
            $query = FuelReport::with(['fuelStation.upazila'])
                ->orderByDesc('created_at');
        } else {
            // Show only latest report per station
            $latestIds = FuelReport::selectRaw('MAX(id) as id')
                ->groupBy('fuel_station_id')
                ->pluck('id');
            
            $query = FuelReport::with(['fuelStation.upazila'])
                ->whereIn('id', $latestIds)
                ->orderByDesc('created_at');
        }
        
        // Filter by phone
        if ($request->has('phone') && $request->phone) {
            $query->where('reporter_phone', 'like', '%' . $request->phone . '%');
        }
        
        // Filter by station
        if ($request->has('station') && $request->station) {
            $query->where('fuel_station_id', $request->station);
        }
        
        // Filter by upazila
        if ($request->has('upazila') && $request->upazila) {
            $query->whereHas('fuelStation', function($q) use ($request) {
                $q->where('upazila_id', $request->upazila);
            });
        }
        
        $reports = $query->paginate(20)->appends($request->query());
        $stations = FuelStation::orderBy('name')->get();
        $upazilas = Upazila::orderBy('name')->get();
        
        // Statistics
        $stats = [
            'total_reports' => FuelReport::count(),
            'today_reports' => FuelReport::whereDate('created_at', today())->count(),
            'unique_reporters' => FuelReport::distinct('reporter_phone')->count('reporter_phone'),
            'stations_with_fuel' => FuelReport::where(function($q) {
                $q->where('petrol_available', true)
                  ->orWhere('diesel_available', true)
                  ->orWhere('octane_available', true);
            })->whereDate('created_at', today())->count(),
        ];
        
        $settings = [
            'is_enabled' => FuelSetting::get('is_enabled', '1'),
        ];
        
        return view('admin.fuel.reports', compact('reports', 'stations', 'upazilas', 'stats', 'settings'));
    }
    
    public function getReportsByPhone($phone)
    {
        $reports = FuelReport::with(['fuelStation.upazila'])
            ->where('reporter_phone', 'like', '%' . $phone . '%')
            ->orderByDesc('created_at')
            ->get();
        
        return response()->json([
            'success' => true,
            'reports' => $reports->map(function($r) {
                return [
                    'id' => $r->id,
                    'station' => $r->fuelStation->name,
                    'upazila' => $r->fuelStation->upazila->name,
                    'name' => $r->reporter_name,
                    'phone' => $r->reporter_phone,
                    'email' => $r->reporter_email,
                    'petrol' => $r->petrol_available ? 'আছে' : 'নেই',
                    'diesel' => $r->diesel_available ? 'আছে' : 'নেই',
                    'octane' => $r->octane_available ? 'আছে' : 'নেই',
                    'petrol_price' => $r->petrol_price,
                    'diesel_price' => $r->diesel_price,
                    'octane_price' => $r->octane_price,
                    'queue' => $r->queue_status_bangla,
                    'notes' => $r->notes,
                    'verified' => $r->is_verified,
                    'date' => $r->created_at->format('d M Y h:i A'),
                ];
            }),
        ]);
    }
    
    public function verifyReport($id)
    {
        $report = FuelReport::findOrFail($id);
        $report->update(['is_verified' => !$report->is_verified]);
        
        return response()->json([
            'success' => true,
            'verified' => $report->is_verified,
            'message' => $report->is_verified ? 'রিপোর্ট ভেরিফাইড হয়েছে।' : 'ভেরিফিকেশন সরানো হয়েছে।',
        ]);
    }
    
    public function deleteReport($id)
    {
        $report = FuelReport::findOrFail($id);
        $report->delete();
        
        return response()->json(['success' => true, 'message' => 'রিপোর্ট মুছে ফেলা হয়েছে।']);
    }
    
    public function showReport($id)
    {
        $report = FuelReport::with(['fuelStation.upazila'])->findOrFail($id);
        return view('admin.fuel.show-report', compact('report'));
    }
    
    public function editReport($id)
    {
        $report = FuelReport::with(['fuelStation.upazila'])->findOrFail($id);
        return view('admin.fuel.edit-report', compact('report'));
    }
    
    public function updateReport(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => 'required|string|max:20',
            'petrol_available' => 'nullable|boolean',
            'diesel_available' => 'nullable|boolean',
            'octane_available' => 'nullable|boolean',
            'petrol_price' => 'nullable|numeric|min:0',
            'petrol_selling_price' => 'nullable|numeric|min:0',
            'diesel_price' => 'nullable|numeric|min:0',
            'diesel_selling_price' => 'nullable|numeric|min:0',
            'octane_price' => 'nullable|numeric|min:0',
            'octane_selling_price' => 'nullable|numeric|min:0',
            'fixed_amount' => 'nullable|numeric|min:0',
            'queue_status' => 'required|in:none,short,medium,long',
            'notes' => 'nullable|string|max:500',
            'is_verified' => 'nullable|boolean',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        $updateData = [
            'reporter_name' => $validated['reporter_name'],
            'reporter_phone' => $validated['reporter_phone'],
            'petrol_available' => $request->boolean('petrol_available'),
            'diesel_available' => $request->boolean('diesel_available'),
            'octane_available' => $request->boolean('octane_available'),
            'petrol_price' => $validated['petrol_price'] ?? null,
            'petrol_selling_price' => $validated['petrol_selling_price'] ?? null,
            'diesel_price' => $validated['diesel_price'] ?? null,
            'diesel_selling_price' => $validated['diesel_selling_price'] ?? null,
            'octane_price' => $validated['octane_price'] ?? null,
            'octane_selling_price' => $validated['octane_selling_price'] ?? null,
            'fixed_amount' => $validated['fixed_amount'] ?? null,
            'queue_status' => $validated['queue_status'],
            'notes' => $validated['notes'] ?? null,
            'is_verified' => $request->boolean('is_verified'),
        ];
        
        // Handle new images
        if ($request->hasFile('new_images')) {
            $imagePaths = $report->images ?? [];
            foreach ($request->file('new_images') as $image) {
                $imageName = 'fuel_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/fuel'), $imageName);
                $imagePaths[] = $imageName;
            }
            $updateData['images'] = $imagePaths;
            $updateData['image'] = $imagePaths[0] ?? $report->image;
        }
        
        $report->update($updateData);
        
        return redirect()->route('admin.fuel.reports')
            ->with('success', 'রিপোর্ট সফলভাবে আপডেট হয়েছে।');
    }
    
    public function deleteReportImage(Request $request, $id)
    {
        $report = FuelReport::findOrFail($id);
        $imageToDelete = $request->input('image');
        
        $images = $report->images ?? [];
        $images = array_values(array_filter($images, fn($img) => $img !== $imageToDelete));
        
        // Delete file from disk
        $filePath = public_path('uploads/fuel/' . $imageToDelete);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $report->update([
            'images' => $images,
            'image' => $images[0] ?? null,
        ]);
        
        return response()->json(['success' => true, 'message' => 'ছবি মুছে ফেলা হয়েছে।']);
    }
    
    // Settings
    public function settings()
    {
        $settings = [
            'is_enabled' => FuelSetting::get('is_enabled', '1'),
            'section_title' => FuelSetting::get('section_title', 'জ্বালানি তেল আপডেট'),
            'section_subtitle' => FuelSetting::get('section_subtitle', 'সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা'),
        ];
        
        return view('admin.fuel.settings', compact('settings'));
    }
    
    public function updateSettings(Request $request)
    {
        FuelSetting::set('is_enabled', $request->boolean('is_enabled') ? '1' : '0');
        FuelSetting::set('section_title', $request->input('section_title', 'জ্বালানি তেল আপডেট'));
        FuelSetting::set('section_subtitle', $request->input('section_subtitle', 'সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা'));
        
        return redirect()->route('admin.fuel.settings')
            ->with('success', 'সেটিংস সফলভাবে আপডেট হয়েছে।');
    }
    
    public function toggleFeature()
    {
        $current = FuelSetting::get('is_enabled', '1');
        $newValue = $current === '1' ? '0' : '1';
        FuelSetting::set('is_enabled', $newValue);
        
        return response()->json([
            'success' => true,
            'enabled' => $newValue === '1',
            'message' => $newValue === '1' ? 'ফিচার চালু করা হয়েছে।' : 'ফিচার বন্ধ করা হয়েছে।',
        ]);
    }
}
