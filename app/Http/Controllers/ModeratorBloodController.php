<?php

namespace App\Http\Controllers;

use App\Models\BloodDonor;
use App\Models\BloodSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ModeratorBloodController extends Controller
{
    private function getModeratorUpazilaIds()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return null; // null = all
        }

        $ids = $user->activeUpazilas()->pluck('upazilas.id')->toArray();

        if (empty($ids) && $user->upazila_id) {
            $ids = [$user->upazila_id];
        }

        return $ids;
    }

    private function scopedDonors()
    {
        $ids = $this->getModeratorUpazilaIds();
        $query = BloodDonor::with('upazila');

        if ($ids !== null) {
            $query->whereIn('upazila_id', $ids);
        }

        return $query;
    }

    private function authorizeDonor($id)
    {
        $donor = BloodDonor::with('upazila')->findOrFail($id);
        $ids = $this->getModeratorUpazilaIds();

        if ($ids !== null && !in_array($donor->upazila_id, $ids)) {
            abort(403, 'আপনার এই ডোনর ম্যানেজ করার অনুমতি নেই।');
        }

        return $donor;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->is_upazila_moderator && !$user->isAdmin()) {
            abort(403);
        }

        $query = $this->scopedDonors();

        if ($request->filled('blood_group')) {
            $query->bloodGroup($request->blood_group);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $donors = $query->latest()->paginate(25)->appends($request->query());

        $upazilaIds = $this->getModeratorUpazilaIds();
        $stats = [
            'total' => $this->scopedDonors()->count(),
            'active' => $this->scopedDonors()->where('status', 'active')->count(),
            'available' => $this->scopedDonors()->active()->where('is_available', true)->count(),
        ];

        $upazilas = $upazilaIds
            ? Upazila::whereIn('id', $upazilaIds)->ordered()->get()
            : Upazila::active()->ordered()->get();

        return view('frontend.dashboard.blood.index', compact('donors', 'stats', 'upazilas'));
    }

    public function show($id)
    {
        $donor = $this->authorizeDonor($id);
        return view('frontend.dashboard.blood.show', compact('donor'));
    }

    public function edit($id)
    {
        $donor = $this->authorizeDonor($id);
        $upazilas = Upazila::active()->ordered()->get();
        return view('frontend.dashboard.blood.edit', compact('donor', 'upazilas'));
    }

    public function update(Request $request, $id)
    {
        $donor = $this->authorizeDonor($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'name', 'phone', 'whatsapp_number', 'email', 'blood_group',
            'last_donation_date', 'is_available', 'hide_phone', 'alternative_contact',
            'type', 'organization_name', 'address', 'status',
        ]);

        if ($request->upazila_id === 'outside') {
            $data['upazila_id'] = null;
            $data['outside_area'] = $request->outside_area;
        } else {
            $data['upazila_id'] = $request->upazila_id;
            $data['outside_area'] = null;
        }

        $donor->update($data);

        if ($request->filled('new_password')) {
            $donor->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->route('dashboard.blood.index')
            ->with('success', 'ডোনর তথ্য আপডেট হয়েছে।');
    }

    public function toggleStatus($id)
    {
        $donor = $this->authorizeDonor($id);
        $donor->update(['status' => $donor->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'স্ট্যাটাস পরিবর্তন হয়েছে।');
    }

    public function resetReachable($id)
    {
        $donor = $this->authorizeDonor($id);
        $donor->update(['not_reachable_count' => 0]);
        return back()->with('success', 'Not reachable count রিসেট হয়েছে।');
    }

    public function myBloodProfile()
    {
        $user = auth()->user();
        $donor = BloodDonor::where('phone', $user->phone)->where('status', 'active')->first();

        if (!$donor) {
            return back()->with('error', 'আপনার ফোন নম্বর দিয়ে কোনো রক্তদাতা প্রোফাইল পাওয়া যায়নি।');
        }

        Session::put('blood_donor_id', $donor->id);
        Session::put('blood_donor_name', $donor->name);

        return redirect()->route('blood.dashboard');
    }
}
