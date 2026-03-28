<?php

namespace App\Http\Controllers;

use App\Models\FuelStation;
use App\Models\FuelReport;
use App\Models\FuelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModeratorFuelController extends Controller
{
    /**
     * Get the upazila IDs this moderator can manage.
     */
    private function getModeratorUpazilaIds()
    {
        $user = auth()->user();

        // Admins shouldn't use this panel but just in case
        if ($user->isAdmin()) {
            return null; // null = all
        }

        // Collect upazila IDs from multiple sources
        $ids = $user->activeUpazilas()->pluck('upazilas.id')->toArray();

        // Fallback to single upazila_id
        if (empty($ids) && $user->upazila_id) {
            $ids = [$user->upazila_id];
        }

        return $ids;
    }

    /**
     * Scope stations query to moderator's upazilas.
     */
    private function scopedStations()
    {
        $ids = $this->getModeratorUpazilaIds();
        $query = FuelStation::with('upazila');

        if ($ids !== null) {
            $query->whereIn('upazila_id', $ids);
        }

        return $query;
    }

    /**
     * List fuel reports for moderator's upazila.
     */
    public function reports(Request $request)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $ids = $this->getModeratorUpazilaIds();
        $stationIds = $this->scopedStations()->pluck('id');

        // Only latest report per station by default
        $showAll = $request->has('show_all') && $request->show_all == '1';

        if ($showAll) {
            $query = FuelReport::with(['fuelStation.upazila'])
                ->whereIn('fuel_station_id', $stationIds)
                ->orderByDesc('created_at');
        } else {
            $latestIds = FuelReport::selectRaw('MAX(id) as id')
                ->whereIn('fuel_station_id', $stationIds)
                ->groupBy('fuel_station_id')
                ->pluck('id');

            $query = FuelReport::with(['fuelStation.upazila'])
                ->whereIn('id', $latestIds)
                ->orderByDesc('created_at');
        }

        if ($request->filled('station')) {
            $query->where('fuel_station_id', $request->station);
        }

        $reports = $query->paginate(20)->appends($request->query());

        $stations = $this->scopedStations()->orderBy('name')->get();

        $stats = [
            'total_reports' => FuelReport::whereIn('fuel_station_id', $stationIds)->count(),
            'today_reports' => FuelReport::whereIn('fuel_station_id', $stationIds)->whereDate('created_at', today())->count(),
            'total_stations' => $stationIds->count(),
        ];

        return view('frontend.dashboard.fuel.reports', compact('reports', 'stations', 'stats'));
    }

    /**
     * Toggle verify on a report.
     */
    public function verifyReport($id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with('fuelStation')->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

        $report->update(['is_verified' => !$report->is_verified]);

        return response()->json([
            'success' => true,
            'verified' => $report->is_verified,
            'message' => $report->is_verified ? 'রিপোর্ট ভেরিফাইড হয়েছে।' : 'ভেরিফিকেশন সরানো হয়েছে।',
        ]);
    }

    /**
     * Show report details.
     */
    public function showReport($id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with(['fuelStation.upazila'])->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

        return view('frontend.dashboard.fuel.show-report', compact('report'));
    }

    /**
     * Edit report form.
     */
    public function editReport($id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with(['fuelStation.upazila'])->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

        return view('frontend.dashboard.fuel.edit-report', compact('report'));
    }

    /**
     * Update report.
     */
    public function updateReport(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with('fuelStation')->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

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

        return redirect()->route('dashboard.fuel.reports')
            ->with('success', 'রিপোর্ট সফলভাবে আপডেট হয়েছে।');
    }

    /**
     * Delete a report image.
     */
    public function deleteReportImage(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with('fuelStation')->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

        $imageToDelete = $request->input('image');
        $images = $report->images ?? [];
        $images = array_values(array_filter($images, fn($img) => $img !== $imageToDelete));

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

    /**
     * Delete a report.
     */
    public function deleteReport($id)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $report = FuelReport::with('fuelStation')->findOrFail($id);
        $this->authorizeStation($report->fuelStation);

        $report->delete();

        return response()->json(['success' => true, 'message' => 'রিপোর্ট মুছে ফেলা হয়েছে।']);
    }

    /**
     * Ensure the station belongs to the moderator's upazila.
     */
    private function authorizeStation(FuelStation $station)
    {
        $ids = $this->getModeratorUpazilaIds();
        if ($ids !== null && !in_array($station->upazila_id, $ids)) {
            abort(403, 'আপনার এই পাম্পে অ্যাক্সেস নেই।');
        }
    }
}
