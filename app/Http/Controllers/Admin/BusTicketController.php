<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusTicket;
use App\Models\BusTicketSeller;
use App\Models\BusTicketSetting;
use Illuminate\Http\Request;

class BusTicketController extends Controller
{
    public function index(Request $request)
    {
        $sellers = BusTicketSeller::withCount(['tickets as available_tickets_count' => function ($q) {
            $q->where('is_sold', false);
        }, 'tickets as sold_tickets_count' => function ($q) {
            $q->where('is_sold', true);
        }])
        ->latest()
        ->paginate(20);

        $totalSellers = BusTicketSeller::count();
        $activeSellers = BusTicketSeller::where('is_active', true)->count();
        $totalTickets = BusTicket::count();
        $availableTickets = BusTicket::where('is_sold', false)->count();
        $soldTickets = BusTicket::where('is_sold', true)->count();

        return view('admin.bus-ticket.index', compact(
            'sellers', 'totalSellers', 'activeSellers',
            'totalTickets', 'availableTickets', 'soldTickets'
        ));
    }

    public function show($id)
    {
        $seller = BusTicketSeller::with(['tickets', 'upazila'])->findOrFail($id);
        return view('admin.bus-ticket.show', compact('seller'));
    }

    public function settings()
    {
        $settings = BusTicketSetting::getSettings();
        return view('admin.bus-ticket.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $settings = BusTicketSetting::first();
        if ($settings) {
            $settings->update($request->only(['title', 'description']));
        } else {
            BusTicketSetting::create($request->only(['title', 'description']));
        }

        return back()->with('success', 'সেটিংস আপডেট হয়েছে');
    }

    public function toggleFeature(Request $request)
    {
        $settings = BusTicketSetting::first();
        if ($settings) {
            $settings->update(['is_enabled' => !$settings->is_enabled]);
        } else {
            BusTicketSetting::create(['is_enabled' => true]);
        }

        $status = $settings && $settings->is_enabled ? 'চালু' : 'বন্ধ';
        return back()->with('success', "বাস টিকেট ফিচার {$status} করা হয়েছে");
    }

    public function toggleSellerStatus($id)
    {
        $seller = BusTicketSeller::findOrFail($id);
        $seller->update(['is_active' => !$seller->is_active]);

        $status = $seller->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়';
        return back()->with('success', "সেলার {$status} করা হয়েছে");
    }

    public function destroySeller($id)
    {
        $seller = BusTicketSeller::findOrFail($id);
        $seller->delete();

        return back()->with('success', 'সেলার এবং তার সব টিকেট মুছে ফেলা হয়েছে');
    }

    public function tickets(Request $request)
    {
        $query = BusTicket::with(['seller', 'seller.upazila'])->latest();

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_sold', false);
            } elseif ($request->status === 'sold') {
                $query->where('is_sold', true);
            }
        }

        if ($request->filled('seller_id')) {
            $query->where('bus_ticket_seller_id', $request->seller_id);
        }

        $tickets = $query->paginate(20);
        $sellers = BusTicketSeller::orderBy('name')->get();

        return view('admin.bus-ticket.tickets', compact('tickets', 'sellers'));
    }

    public function showTicket($id)
    {
        $ticket = BusTicket::with(['seller', 'seller.upazila'])->findOrFail($id);
        return view('admin.bus-ticket.show-ticket', compact('ticket'));
    }

    public function destroyTicket($id)
    {
        $ticket = BusTicket::findOrFail($id);
        $ticket->delete();

        return back()->with('success', 'টিকেট মুছে ফেলা হয়েছে');
    }
}
