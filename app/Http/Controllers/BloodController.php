<?php

namespace App\Http\Controllers;

use App\Models\BloodDonor;
use App\Models\BloodDonationHistory;
use App\Models\BloodComment;
use App\Models\BloodSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class BloodController extends Controller
{
    private function checkEnabled()
    {
        if (!BloodSetting::isEnabled()) {
            abort(404);
        }
    }

    // ========== PUBLIC PAGES ==========

    public function index(Request $request)
    {
        $this->checkEnabled();
        BloodSetting::set('page_views', (int)BloodSetting::get('page_views', 0) + 1);

        $upazilas = Upazila::active()->ordered()->get();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        $query = BloodDonor::active()->with('upazila');

        // Filters
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
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('available_only')) {
            $query->available();
        }

        $donors = $query->latest()->paginate(24)->appends($request->query());

        // Stats
        $totalDonors = BloodDonor::active()->count();
        $availableDonors = BloodDonor::available()->count();
        $totalOrganizations = BloodDonor::active()->organization()->distinct('organization_name')->count('organization_name');

        return view('frontend.blood.index', compact(
            'donors', 'upazilas', 'bloodGroups',
            'totalDonors', 'availableDonors', 'totalOrganizations'
        ));
    }

    public function show($id)
    {
        $this->checkEnabled();
        $donor = BloodDonor::active()->with(['upazila', 'comments' => function ($q) {
            $q->approved()->latest()->limit(20);
        }])->findOrFail($id);

        return view('frontend.blood.show', compact('donor'));
    }

    // ========== REGISTRATION & AUTH ==========

    public function registerForm()
    {
        $this->checkEnabled();
        if (Session::has('blood_donor_id')) {
            return redirect()->route('blood.dashboard');
        }
        $upazilas = Upazila::active()->ordered()->get();
        return view('frontend.blood.register', compact('upazilas'));
    }

    public function register(Request $request)
    {
        $this->checkEnabled();

        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:4|confirmed',
            'blood_group' => $request->type === 'organization' ? 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-' : 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'last_donation_date' => 'nullable|date|before_or_equal:today',
            'type' => 'required|in:individual,organization',
            'organization_name' => 'required_if:type,organization|nullable|string|max:150',
            'upazila_id' => 'required_without:outside_area|nullable|exists:upazilas,id',
            'outside_area' => 'required_without:upazila_id|nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'hide_phone' => 'nullable|boolean',
            'alternative_contact' => 'nullable|string|max:255',
            'available_areas' => 'nullable|array',
            'available_areas.*' => 'exists:upazilas,id',
        ];

        // If "outside" is selected in upazila dropdown, set upazila_id to null
        if ($request->upazila_id === 'outside') {
            $request->merge(['upazila_id' => null]);
        }

        $request->validate($rules, [
            'name.required' => 'নাম দিন',
            'phone.required' => 'ফোন নম্বর দিন',
            'password.required' => 'পাসওয়ার্ড দিন',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৪ অক্ষর হতে হবে',
            'blood_group.required' => 'রক্তের গ্রুপ নির্বাচন করুন',
            'upazila_id.required_without' => 'উপজেলা নির্বাচন করুন অথবা বাহিরের এলাকা লিখুন',
            'outside_area.required_without' => 'বর্তমান এলাকা/শহরের নাম লিখুন',
            'organization_name.required_if' => 'সংগঠনের নাম দিন',
        ]);

        // Check duplicate: individual = unique phone, org = unique phone+org_name
        if ($request->type === 'individual') {
            $exists = BloodDonor::where('phone', $request->phone)
                ->where('type', 'individual')->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['phone' => 'এই ফোন নম্বর দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে।']);
            }
        } else {
            $exists = BloodDonor::where('phone', $request->phone)
                ->where('type', 'organization')
                ->where('organization_name', $request->organization_name)->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['phone' => 'এই ফোন নম্বর দিয়ে এই সংগঠনে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে।']);
            }
        }

        $donor = BloodDonor::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'blood_group' => $request->blood_group,
            'last_donation_date' => $request->last_donation_date,
            'type' => $request->type,
            'organization_name' => $request->type === 'organization' ? $request->organization_name : null,
            'upazila_id' => $request->upazila_id,
            'outside_area' => $request->outside_area,
            'address' => $request->address,
            'hide_phone' => $request->boolean('hide_phone'),
            'alternative_contact' => $request->alternative_contact,
            'available_areas' => $request->available_areas,
            'is_available' => true,
            'status' => 'active',
        ]);

        Session::put('blood_donor_id', $donor->id);
        Session::put('blood_donor_name', $donor->name);

        return redirect()->route('blood.dashboard')
            ->with('success', 'রেজিস্ট্রেশন সফল হয়েছে! আপনি এখন রক্তদাতা হিসেবে তালিকাভুক্ত।');
    }

    public function loginForm()
    {
        $this->checkEnabled();
        if (Session::has('blood_donor_id')) {
            return redirect()->route('blood.dashboard');
        }
        return view('frontend.blood.login');
    }

    public function login(Request $request)
    {
        $this->checkEnabled();

        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find donor by phone (if org, also need org name)
        $query = BloodDonor::where('phone', $request->phone);
        if ($request->filled('organization_name')) {
            $query->where('organization_name', $request->organization_name);
        }

        $donors = $query->get();

        foreach ($donors as $donor) {
            if (Hash::check($request->password, $donor->password)) {
                Session::put('blood_donor_id', $donor->id);
                Session::put('blood_donor_name', $donor->name);
                return redirect()->route('blood.dashboard')
                    ->with('success', 'লগইন সফল হয়েছে!');
            }
        }

        return back()->withInput()->withErrors(['phone' => 'ফোন নম্বর বা পাসওয়ার্ড ভুল।']);
    }

    public function logout()
    {
        Session::forget('blood_donor_id');
        Session::forget('blood_donor_name');
        return redirect()->route('blood.index')->with('success', 'লগআউট সফল হয়েছে।');
    }

    public function forgotPassword()
    {
        $this->checkEnabled();
        return view('frontend.blood.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $this->checkEnabled();
        $request->validate(['phone' => 'required|string']);

        $donor = BloodDonor::where('phone', $request->phone)->first();
        if (!$donor || !$donor->email) {
            return back()->withErrors(['phone' => 'এই নম্বর দিয়ে কোনো একাউন্ট পাওয়া যায়নি অথবা ইমেইল নেই।']);
        }

        // Generate temp password
        $tempPassword = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $donor->update(['password' => Hash::make($tempPassword)]);

        // Simple email via Laravel Mail
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "আপনার Explore Blood একাউন্টের নতুন পাসওয়ার্ড: {$tempPassword}\n\nলগইন করে পাসওয়ার্ড পরিবর্তন করুন।\n\n- Explore Satkhira",
                function ($message) use ($donor) {
                    $message->to($donor->email)
                        ->subject('Explore Blood - পাসওয়ার্ড রিসেট');
                }
            );
            return back()->with('success', 'আপনার ইমেইলে নতুন পাসওয়ার্ড পাঠানো হয়েছে।');
        } catch (\Exception $e) {
            return back()->withErrors(['phone' => 'ইমেইল পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।']);
        }
    }

    // ========== DONOR DASHBOARD ==========

    public function dashboard()
    {
        $this->checkEnabled();
        $donor = $this->getAuthDonor();
        if (!$donor) return redirect()->route('blood.login');

        return view('frontend.blood.dashboard', compact('donor'));
    }

    public function updateProfile(Request $request)
    {
        $this->checkEnabled();
        $donor = $this->getAuthDonor();
        if (!$donor) return redirect()->route('blood.login');

        $request->validate([
            'name' => 'required|string|max:100',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'outside_area' => 'nullable|string|max:255',
            'hide_phone' => 'nullable|boolean',
            'alternative_contact' => 'nullable|string|max:255',
            'available_areas' => 'nullable|array',
            'available_for' => 'nullable|array',
        ]);

        // Handle upazila/outside area toggle
        $updateData = [
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'address' => $request->address,
            'hide_phone' => $request->boolean('hide_phone'),
            'alternative_contact' => $request->alternative_contact,
            'available_areas' => $request->available_areas,
            'available_for' => $request->available_for,
        ];

        if ($request->upazila_id === 'outside') {
            $updateData['upazila_id'] = null;
            $updateData['outside_area'] = $request->outside_area;
        } elseif ($request->filled('upazila_id')) {
            $updateData['upazila_id'] = $request->upazila_id;
            $updateData['outside_area'] = null;
        }

        $donor->update($updateData);

        return back()->with('success', 'তথ্য আপডেট হয়েছে।');
    }

    public function changePassword(Request $request)
    {
        $this->checkEnabled();
        $donor = $this->getAuthDonor();
        if (!$donor) return redirect()->route('blood.login');

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ]);

        if (!Hash::check($request->current_password, $donor->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড ভুল।']);
        }

        $donor->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'পাসওয়ার্ড পরিবর্তন হয়েছে।');
    }

    public function toggleAvailability()
    {
        $this->checkEnabled();
        $donor = $this->getAuthDonor();
        if (!$donor) return redirect()->route('blood.login');

        $donor->update(['is_available' => !$donor->is_available]);
        $status = $donor->is_available ? 'Available' : 'Unavailable';
        return back()->with('success', "আপনি এখন {$status} হিসেবে চিহ্নিত।");
    }

    public function recordDonation(Request $request)
    {
        $this->checkEnabled();
        $donor = $this->getAuthDonor();
        if (!$donor) return redirect()->route('blood.login');

        $request->validate([
            'donation_date' => 'required|date|before_or_equal:today',
        ]);

        $donor->update(['last_donation_date' => $request->donation_date]);

        // Create donation history entry
        BloodDonationHistory::create([
            'blood_donor_id' => $donor->id,
            'donation_date' => $request->donation_date,
        ]);

        return back()->with('success', 'রক্তদানের তারিখ আপডেট হয়েছে। ' . BloodSetting::cooldownDays() . ' দিন পর আপনি আবার Available দেখাবে।');
    }

    // ========== VISITOR ACTIONS ==========

    public function reportNotReachable($id)
    {
        $this->checkEnabled();
        $donor = BloodDonor::active()->findOrFail($id);

        // Prevent spam: one report per IP per donor per day
        $sessionKey = "blood_reported_{$id}";
        if (Session::has($sessionKey)) {
            return back()->with('info', 'আপনি ইতিমধ্যে রিপোর্ট করেছেন।');
        }

        $donor->increment('not_reachable_count');
        Session::put($sessionKey, true);

        $threshold = BloodSetting::notReachableThreshold();
        if ($donor->not_reachable_count >= $threshold) {
            return back()->with('info', 'ধন্যবাদ। পর্যাপ্ত রিপোর্টের কারণে এই ডোনরকে সাময়িকভাবে অনুপলব্ধ করা হয়েছে।');
        }

        return back()->with('success', 'রিপোর্ট করা হয়েছে। ধন্যবাদ।');
    }

    public function storeComment(Request $request, $id)
    {
        $this->checkEnabled();
        $donor = BloodDonor::active()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'comment' => 'required|string|max:500',
        ], [
            'name.required' => 'নাম দিন',
            'comment.required' => 'মন্তব্য লিখুন',
        ]);

        BloodComment::create([
            'blood_donor_id' => $id,
            'name' => $request->name,
            'phone' => $request->phone,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'মন্তব্য করা হয়েছে।');
    }

    // ========== HELPERS ==========

    private function getAuthDonor()
    {
        $id = Session::get('blood_donor_id');
        if (!$id) return null;
        $donor = BloodDonor::with('upazila')->find($id);
        if (!$donor) {
            Session::forget('blood_donor_id');
            Session::forget('blood_donor_name');
            return null;
        }
        return $donor;
    }

    // ========== ORGANIZATION DONOR MANAGEMENT ==========

    public function orgDonors()
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $donors = BloodDonor::where('parent_id', $org->id)
            ->with('upazila')
            ->withCount('donationHistories')
            ->latest()
            ->paginate(25);

        return view('frontend.blood.org-donors', compact('org', 'donors'));
    }

    public function orgAddDonorForm()
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $upazilas = Upazila::active()->ordered()->get();
        return view('frontend.blood.org-add-donor', compact('org', 'upazilas'));
    }

    public function orgStoreDonor(Request $request)
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        ]);

        // Check duplicate under this org
        $exists = BloodDonor::where('phone', $request->phone)
            ->where('parent_id', $org->id)
            ->exists();
        if ($exists) {
            return back()->withInput()->with('error', 'এই ফোন নম্বর দিয়ে ইতিমধ্যে এই সংগঠনে ডোনর আছে।');
        }

        BloodDonor::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'blood_group' => $request->blood_group,
            'type' => 'individual',
            'parent_id' => $org->id,
            'organization_name' => $org->organization_name,
            'upazila_id' => $request->upazila_id === 'outside' ? null : $request->upazila_id,
            'outside_area' => $request->upazila_id === 'outside' ? $request->outside_area : null,
            'last_donation_date' => $request->last_donation_date,
            'password' => Hash::make($request->phone), // default password = phone
            'status' => 'active',
            'is_available' => true,
        ]);

        return redirect()->route('blood.org-donors')->with('success', 'ডোনর সফলভাবে যুক্ত হয়েছে।');
    }

    public function orgEditDonor($id)
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $donor = BloodDonor::where('parent_id', $org->id)->findOrFail($id);
        $upazilas = Upazila::active()->ordered()->get();
        return view('frontend.blood.org-edit-donor', compact('org', 'donor', 'upazilas'));
    }

    public function orgUpdateDonor(Request $request, $id)
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $donor = BloodDonor::where('parent_id', $org->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        ]);

        $oldDate = $donor->last_donation_date?->toDateString();

        $donor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'blood_group' => $request->blood_group,
            'last_donation_date' => $request->last_donation_date,
            'is_available' => $request->boolean('is_available', true),
            'upazila_id' => $request->upazila_id === 'outside' ? null : $request->upazila_id,
            'outside_area' => $request->upazila_id === 'outside' ? $request->outside_area : null,
        ]);

        // Track donation history if date changed
        if ($request->last_donation_date && $request->last_donation_date !== $oldDate) {
            BloodDonationHistory::create([
                'blood_donor_id' => $donor->id,
                'donation_date' => $request->last_donation_date,
            ]);
        }

        return redirect()->route('blood.org-donors')->with('success', 'ডোনর তথ্য আপডেট হয়েছে।');
    }

    public function orgToggleDonor($id)
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $donor = BloodDonor::where('parent_id', $org->id)->findOrFail($id);
        $donor->update(['is_available' => !$donor->is_available]);

        $status = $donor->is_available ? 'Available' : 'Not Available';
        return back()->with('success', "ডোনর এখন {$status}।");
    }

    public function orgDeleteDonor($id)
    {
        $this->checkEnabled();
        $org = $this->getAuthDonor();
        if (!$org) return redirect()->route('blood.login');
        if ($org->type !== 'organization') abort(403);

        $donor = BloodDonor::where('parent_id', $org->id)->findOrFail($id);
        $donor->delete();

        return back()->with('success', 'ডোনর মুছে ফেলা হয়েছে।');
    }
}
