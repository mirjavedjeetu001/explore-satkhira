<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodDonor;
use App\Models\BloodComment;
use App\Models\BloodSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BloodController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodDonor::with('upazila');

        if ($request->filled('blood_group')) {
            $query->bloodGroup($request->blood_group);
        }
        if ($request->filled('upazila')) {
            if ($request->upazila === 'outside') {
                $query->whereNull('upazila_id');
            } else {
                $query->inUpazila($request->upazila);
            }
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $donors = $query->latest()->paginate(25)->appends($request->query());
        $upazilas = Upazila::active()->ordered()->get();

        $stats = [
            'total' => BloodDonor::count(),
            'active' => BloodDonor::active()->count(),
            'available' => BloodDonor::available()->count(),
            'organizations' => BloodDonor::active()->organization()->distinct('organization_name')->count('organization_name'),
            'page_views' => BloodSetting::get('page_views', 0),
        ];

        return view('admin.blood.index', compact('donors', 'upazilas', 'stats'));
    }

    public function show($id)
    {
        $donor = BloodDonor::with(['upazila', 'comments' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.blood.show', compact('donor'));
    }

    public function edit($id)
    {
        $donor = BloodDonor::findOrFail($id);
        $upazilas = Upazila::active()->ordered()->get();
        return view('admin.blood.edit', compact('donor', 'upazilas'));
    }

    public function update(Request $request, $id)
    {
        $donor = BloodDonor::findOrFail($id);

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

        return redirect()->route('admin.blood.index')
            ->with('success', 'ডোনর তথ্য আপডেট হয়েছে।');
    }

    public function destroy($id)
    {
        BloodDonor::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'ডোনর মুছে ফেলা হয়েছে।']);
    }

    public function toggleStatus($id)
    {
        $donor = BloodDonor::findOrFail($id);
        $donor->update(['status' => $donor->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'স্ট্যাটাস পরিবর্তন হয়েছে।');
    }

    public function resetReachable($id)
    {
        $donor = BloodDonor::findOrFail($id);
        $donor->update(['not_reachable_count' => 0]);
        return back()->with('success', 'Not reachable count রিসেট হয়েছে।');
    }

    // Comments management
    public function comments(Request $request)
    {
        $comments = BloodComment::with('donor')
            ->latest()
            ->paginate(25)
            ->appends($request->query());

        return view('admin.blood.comments', compact('comments'));
    }

    public function deleteComment($id)
    {
        BloodComment::findOrFail($id)->delete();
        return back()->with('success', 'মন্তব্য মুছে ফেলা হয়েছে।');
    }

    // Settings
    public function settings()
    {
        $settings = [
            'is_enabled' => BloodSetting::get('is_enabled', '1'),
            'cooldown_days' => BloodSetting::get('cooldown_days', 90),
            'not_reachable_threshold' => BloodSetting::get('not_reachable_threshold', 10),
            'show_on_homepage' => BloodSetting::get('show_on_homepage', '0'),
        ];
        return view('admin.blood.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'cooldown_days' => 'required|integer|min:1|max:365',
            'not_reachable_threshold' => 'required|integer|min:1|max:100',
        ]);

        BloodSetting::set('cooldown_days', $request->cooldown_days);
        BloodSetting::set('not_reachable_threshold', $request->not_reachable_threshold);

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }

    public function toggleFeature()
    {
        $current = BloodSetting::get('is_enabled', '1');
        $new = $current === '1' ? '0' : '1';
        BloodSetting::set('is_enabled', $new);

        return response()->json([
            'success' => true,
            'enabled' => $new === '1',
            'message' => $new === '1' ? 'Explore Blood চালু করা হয়েছে' : 'Explore Blood বন্ধ করা হয়েছে',
        ]);
    }

    public function toggleHomepage()
    {
        $current = BloodSetting::get('show_on_homepage', '0');
        $new = $current === '1' ? '0' : '1';
        BloodSetting::set('show_on_homepage', $new);

        return response()->json([
            'success' => true,
            'enabled' => $new === '1',
            'message' => $new === '1' ? 'হোমপেজে রক্তদাতা সেকশন চালু হয়েছে' : 'হোমপেজে রক্তদাতা সেকশন বন্ধ হয়েছে',
        ]);
    }

    // Add donor from admin panel
    public function create()
    {
        $upazilas = Upazila::active()->ordered()->get();
        return view('admin.blood.create', compact('upazilas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:4',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'type' => 'required|in:individual,organization',
        ]);

        $upazilaId = $request->upazila_id === 'outside' ? null : $request->upazila_id;
        $outsideArea = $request->upazila_id === 'outside' ? $request->outside_area : null;

        BloodDonor::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'blood_group' => $request->blood_group,
            'last_donation_date' => $request->last_donation_date,
            'type' => $request->type,
            'organization_name' => $request->type === 'organization' ? $request->organization_name : null,
            'upazila_id' => $upazilaId,
            'outside_area' => $outsideArea,
            'address' => $request->address,
            'hide_phone' => $request->boolean('hide_phone'),
            'alternative_contact' => $request->alternative_contact,
            'is_available' => true,
            'status' => 'active',
        ]);

        return redirect()->route('admin.blood.index')
            ->with('success', 'নতুন ডোনর যোগ করা হয়েছে।');
    }
}
