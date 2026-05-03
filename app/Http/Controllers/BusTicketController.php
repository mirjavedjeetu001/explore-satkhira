<?php

namespace App\Http\Controllers;

use App\Models\BusTicket;
use App\Models\BusTicketSeller;
use App\Models\BusTicketSetting;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BusTicketController extends Controller
{
    private function normalizePhone(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        return preg_replace('/\D+/', '', $value);
    }

    private function requireFeatureEnabled()
    {
        if (!BusTicketSetting::isEnabled()) {
            return redirect('/')->with('error', 'বাস টিকেট বেচাকেনা ফিচার বর্তমানে বন্ধ আছে।');
        }
        return null;
    }

    private function requireSellerLogin()
    {
        if (!session('bus_ticket_seller_id')) {
            return redirect()->route('bus-ticket.login')->with('error', 'অনুগ্রহ করে প্রথমে লগইন করুন।');
        }
        return null;
    }

    // ========== PUBLIC PAGES ==========

    public function index(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $settings = BusTicketSetting::getSettings();
        $upazilas = Upazila::orderBy('name')->get();

        $availableTickets = BusTicket::with(['seller.upazila'])
            ->where('is_sold', false)
            ->where('journey_date', '>=', today())
            ->latest()
            ->paginate(12, ['*'], 'available_page');

        $soldTickets = BusTicket::with(['seller.upazila'])
            ->where('is_sold', true)
            ->latest()
            ->paginate(6, ['*'], 'sold_page');

        return view('frontend.bus-ticket.index', compact(
            'settings', 'upazilas', 'availableTickets', 'soldTickets'
        ));
    }

    public function filterByUpazila(Request $request, $upazilaId)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $settings = BusTicketSetting::getSettings();
        $upazilas = Upazila::orderBy('name')->get();
        $selectedUpazila = Upazila::findOrFail($upazilaId);

        $sellerIds = BusTicketSeller::where('upazila_id', $upazilaId)->pluck('id');

        $availableTickets = BusTicket::with(['seller.upazila'])
            ->whereIn('bus_ticket_seller_id', $sellerIds)
            ->where('is_sold', false)
            ->where('journey_date', '>=', today())
            ->latest()
            ->paginate(12);

        $soldTickets = BusTicket::with(['seller.upazila'])
            ->whereIn('bus_ticket_seller_id', $sellerIds)
            ->where('is_sold', true)
            ->latest()
            ->paginate(6);

        return view('frontend.bus-ticket.index', compact(
            'settings', 'upazilas', 'selectedUpazila', 'availableTickets', 'soldTickets'
        ));
    }

    public function interested(Request $request, $id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $ticket = BusTicket::findOrFail($id);
        $ticket->incrementInterested();

        return response()->json(['success' => true, 'count' => $ticket->interested_count]);
    }

    // ========== REGISTRATION & AUTH ==========

    public function registerForm()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        if (session('bus_ticket_seller_id')) {
            return redirect()->route('bus-ticket.dashboard');
        }

        $upazilas = Upazila::orderBy('name')->get();
        return view('frontend.bus-ticket.register', compact('upazilas'));
    }

    public function register(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $request->merge([
            'phone' => $this->normalizePhone($request->phone),
            'whatsapp' => $this->normalizePhone($request->whatsapp),
        ]);

        $request->merge([
            'contact_number' => $this->normalizePhone($request->contact_number ?: $request->phone),
            'whatsapp_number' => $this->normalizePhone($request->whatsapp_number),
        ]);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|min:10|max:20|unique:bus_ticket_sellers,phone',
            'password' => 'required|string|min:4|confirmed',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'address' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            // First ticket fields (required at registration)
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
            'journey_date' => 'required|date|after_or_equal:today',
            'bus_name' => 'nullable|string|max:100',
            'ticket_type' => 'required|in:seat,ac,sleeper,deluxe',
            'seat_count' => 'required|integer|min:1|max:10',
            'price_per_ticket' => 'required|numeric|min:0',
            'contact_number' => 'required|string|min:10|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'নাম দিন',
            'phone.required' => 'ফোন নম্বর দিন',
            'phone.unique' => 'এই ফোন নম্বরে ইতিমধ্যে রেজিস্ট্রেশন করা আছে',
            'password.required' => 'পাসওয়ার্ড দিন',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৪ অক্ষর হতে হবে',
            'from_location.required' => 'কোথা থেকে যাচ্ছেন তা দিন',
            'to_location.required' => 'কোথায় যাচ্ছেন তা দিন',
            'journey_date.required' => 'ভ্রমণের তারিখ দিন',
            'journey_date.after_or_equal' => 'ভ্রমণের তারিখ আজ বা পরবর্তী হতে হবে',
            'ticket_type.required' => 'টিকেটের ধরন নির্বাচন করুন',
            'seat_count.required' => 'সিট সংখ্যা দিন',
            'price_per_ticket.required' => 'টিকেটের দাম দিন',
            'contact_number.required' => 'যোগাযোগের নম্বর দিন',
        ]);

        $seller = BusTicketSeller::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'whatsapp' => $request->whatsapp,
            'upazila_id' => $request->upazila_id,
            'address' => $request->address,
            'is_active' => true,
        ]);

        BusTicket::create([
            'bus_ticket_seller_id' => $seller->id,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'journey_date' => $request->journey_date,
            'bus_name' => $request->bus_name,
            'ticket_type' => $request->ticket_type,
            'seat_count' => $request->seat_count,
            'price_per_ticket' => $request->price_per_ticket,
            'contact_number' => $request->contact_number,
            'whatsapp_number' => $request->whatsapp_number,
            'description' => $request->description,
            'is_sold' => false,
            'interested_count' => 0,
        ]);

        session(['bus_ticket_seller_id' => $seller->id]);

        return redirect()->route('bus-ticket.dashboard')->with('success', 'সফলভাবে রেজিস্ট্রেশন এবং প্রথম টিকেট পোস্ট হয়েছে!');
    }

    public function loginForm()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        if (session('bus_ticket_seller_id')) {
            return redirect()->route('bus-ticket.dashboard');
        }

        return view('frontend.bus-ticket.login');
    }

    public function login(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;

        $request->merge(['phone' => $this->normalizePhone($request->phone)]);

        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ], [
            'phone.required' => 'ফোন নম্বর দিন',
            'password.required' => 'পাসওয়ার্ড দিন',
        ]);

        $seller = BusTicketSeller::where('phone', $request->phone)->first();

        if (!$seller || !Hash::check($request->password, $seller->password)) {
            return back()->withErrors(['phone' => 'ফোন নম্বর বা পাসওয়ার্ড ভুল'])->withInput();
        }

        if (!$seller->is_active) {
            return back()->withErrors(['phone' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে'])->withInput();
        }

        session(['bus_ticket_seller_id' => $seller->id]);

        return redirect()->route('bus-ticket.dashboard')->with('success', 'সফলভাবে লগইন হয়েছে!');
    }

    public function logout()
    {
        session()->forget('bus_ticket_seller_id');
        return redirect()->route('bus-ticket.index')->with('success', 'লগআউট সম্পন্ন');
    }

    // ========== SELLER DASHBOARD ==========

    public function dashboard()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $availableTickets = $seller->availableTickets()->paginate(10);
        $soldTickets = $seller->soldTickets()->paginate(10);

        return view('frontend.bus-ticket.dashboard', compact('seller', 'availableTickets', 'soldTickets'));
    }

    public function createTicketForm()
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        return view('frontend.bus-ticket.create-ticket');
    }

    public function storeTicket(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $request->merge([
            'contact_number' => $this->normalizePhone($request->contact_number),
            'whatsapp_number' => $this->normalizePhone($request->whatsapp_number),
        ]);

        $request->validate([
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
            'journey_date' => 'required|date|after_or_equal:today',
            'bus_name' => 'nullable|string|max:100',
            'ticket_type' => 'required|in:seat,ac,sleeper,deluxe',
            'seat_count' => 'required|integer|min:1|max:10',
            'price_per_ticket' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'contact_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
        ], [
            'from_location.required' => 'কোথা থেকে যাবেন তা দিন',
            'to_location.required' => 'কোথায় যাবেন তা দিন',
            'journey_date.required' => 'ভ্রমণের তারিখ দিন',
            'journey_date.after_or_equal' => 'আজকের তারিখ বা পরের তারিখ দিন',
            'price_per_ticket.required' => 'টিকেটের দাম দিন',
            'contact_number.required' => 'যোগাযোগের নম্বর দিন',
        ]);

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));

        BusTicket::create([
            'bus_ticket_seller_id' => $seller->id,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'journey_date' => $request->journey_date,
            'bus_name' => $request->bus_name,
            'ticket_type' => $request->ticket_type,
            'seat_count' => $request->seat_count,
            'price_per_ticket' => $request->price_per_ticket,
            'description' => $request->description,
            'contact_number' => $request->contact_number ?? $seller->phone,
            'whatsapp_number' => $request->whatsapp_number ?? $seller->whatsapp,
            'is_sold' => false,
            'interested_count' => 0,
        ]);

        return redirect()->route('bus-ticket.dashboard')->with('success', 'টিকেট বিজ্ঞাপন সফলভাবে পোস্ট হয়েছে!');
    }

    public function editTicket($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $ticket = BusTicket::where('bus_ticket_seller_id', $seller->id)->findOrFail($id);

        return view('frontend.bus-ticket.edit-ticket', compact('ticket'));
    }

    public function updateTicket(Request $request, $id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $ticket = BusTicket::where('bus_ticket_seller_id', $seller->id)->findOrFail($id);

        $request->merge([
            'contact_number' => $this->normalizePhone($request->contact_number),
            'whatsapp_number' => $this->normalizePhone($request->whatsapp_number),
        ]);

        $request->validate([
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
            'journey_date' => 'required|date',
            'bus_name' => 'nullable|string|max:100',
            'ticket_type' => 'required|in:seat,ac,sleeper,deluxe',
            'seat_count' => 'required|integer|min:1|max:10',
            'price_per_ticket' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'contact_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $ticket->update($request->only([
            'from_location', 'to_location', 'journey_date', 'bus_name',
            'ticket_type', 'seat_count', 'price_per_ticket', 'description',
            'contact_number', 'whatsapp_number'
        ]));

        return redirect()->route('bus-ticket.dashboard')->with('success', 'টিকেট বিজ্ঞাপন আপডেট হয়েছে!');
    }

    public function markAsSold($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $ticket = BusTicket::where('bus_ticket_seller_id', $seller->id)->findOrFail($id);

        $ticket->markAsSold();

        return back()->with('success', 'টিকেট বিক্রয় হয়েছে হিসেবে চিহ্নিত করা হয়েছে!');
    }

    public function markAsAvailable($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $ticket = BusTicket::where('bus_ticket_seller_id', $seller->id)->findOrFail($id);

        $ticket->update([
            'is_sold' => false,
            'sold_at' => null,
        ]);

        return back()->with('success', 'টিকেট পুনরায় সক্রিয় করা হয়েছে!');
    }

    public function deleteTicket($id)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));
        $ticket = BusTicket::where('bus_ticket_seller_id', $seller->id)->findOrFail($id);

        $ticket->delete();

        return back()->with('success', 'টিকেট বিজ্ঞাপন মুছে ফেলা হয়েছে!');
    }

    public function updateProfile(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));

        $request->merge([
            'whatsapp' => $this->normalizePhone($request->whatsapp),
        ]);

        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        $seller->update($request->only(['name', 'address', 'whatsapp']));

        return back()->with('success', 'প্রোফাইল আপডেট হয়েছে!');
    }

    public function changePassword(Request $request)
    {
        if ($redirect = $this->requireFeatureEnabled()) return $redirect;
        if ($redirect = $this->requireSellerLogin()) return $redirect;

        $seller = BusTicketSeller::find(session('bus_ticket_seller_id'));

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:4|confirmed',
        ], [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড দিন',
            'password.required' => 'নতুন পাসওয়ার্ড দিন',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৪ অক্ষর হতে হবে',
        ]);

        if (!Hash::check($request->current_password, $seller->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড ভুল']);
        }

        $seller->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'পাসওয়ার্ড পরিবর্তন হয়েছে!');
    }
}
