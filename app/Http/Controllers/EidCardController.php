<?php

namespace App\Http\Controllers;

use App\Models\EidCard;
use App\Models\EidCardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EidCardController extends Controller
{
    /**
     * Show the Eid Card maker page
     */
    public function index()
    {
        $settings = EidCardSetting::getSettings();
        
        // If feature is disabled, show message
        if (!$settings->is_enabled) {
            return view('frontend.eid-card.disabled', compact('settings'));
        }
        
        // Check for saved phone in cookie
        $savedPhone = request()->cookie('eid_card_phone');
        $savedName = request()->cookie('eid_card_name');
        $hasSession = !empty($savedPhone);
        
        // Get user's previous cards
        $previousCards = [];
        if ($hasSession) {
            $previousCards = EidCard::where('phone', $savedPhone)
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('frontend.eid-card.index', compact(
            'settings',
            'hasSession',
            'savedPhone',
            'savedName',
            'previousCards'
        ));
    }
    
    /**
     * Start session with phone
     */
    public function startSession(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|size:11',
        ]);
        
        $phone = $request->phone;
        $name = $request->name;
        
        // Set cookies for 30 days
        $cookiePhone = cookie('eid_card_phone', $phone, 60 * 24 * 30);
        $cookieName = cookie('eid_card_name', $name, 60 * 24 * 30);
        
        // Check if user has previous cards
        $previousCards = EidCard::where('phone', $phone)->count();
        
        return response()->json([
            'success' => true,
            'has_previous_data' => $previousCards > 0,
        ])->withCookie($cookiePhone)->withCookie($cookieName);
    }
    
    /**
     * Create new Eid card
     */
    public function createCard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'custom_message' => 'nullable|string|max:200',
            'template' => 'required|in:template1,template2,template3',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $phone = request()->cookie('eid_card_phone');
        
        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'সেশন শেষ হয়ে গেছে। পেজ রিফ্রেশ করুন।',
            ], 401);
        }
        
        // Upload photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'eid-card-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('eid-cards', $filename, 'public');
        }
        
        // Create card record
        $card = EidCard::create([
            'phone' => $phone,
            'name' => $request->name,
            'designation' => $request->designation,
            'custom_message' => $request->custom_message,
            'template' => $request->template,
            'photo' => $photoPath,
        ]);
        
        return response()->json([
            'success' => true,
            'card' => [
                'id' => $card->id,
                'name' => $card->name,
                'designation' => $card->designation,
                'custom_message' => $card->custom_message,
                'template' => $card->template,
                'photo_url' => $photoPath ? asset('storage/' . $photoPath) : null,
            ],
        ]);
    }
    
    /**
     * Get user's previous cards
     */
    public function getCards()
    {
        $phone = request()->cookie('eid_card_phone');
        
        if (!$phone) {
            return response()->json(['cards' => []]);
        }
        
        $cards = EidCard::where('phone', $phone)
            ->latest()
            ->take(10)
            ->get()
            ->map(function($card) {
                return [
                    'id' => $card->id,
                    'name' => $card->name,
                    'designation' => $card->designation,
                    'custom_message' => $card->custom_message,
                    'template' => $card->template,
                    'photo_url' => $card->photo ? asset('storage/' . $card->photo) : null,
                    'created_at' => $card->created_at->format('d M, Y h:i A'),
                ];
            });
        
        return response()->json(['cards' => $cards]);
    }
    
    /**
     * Delete a card
     */
    public function deleteCard($id)
    {
        $phone = request()->cookie('eid_card_phone');
        
        $card = EidCard::where('id', $id)->where('phone', $phone)->first();
        
        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'কার্ড পাওয়া যায়নি।',
            ], 404);
        }
        
        // Delete photo
        if ($card->photo) {
            Storage::disk('public')->delete($card->photo);
        }
        
        $card->delete();
        
        return response()->json([
            'success' => true,
        ]);
    }
    
    /**
     * Logout / Reset session
     */
    public function resetSession()
    {
        return response()->json([
            'success' => true,
        ])->withCookie(cookie()->forget('eid_card_phone'))
          ->withCookie(cookie()->forget('eid_card_name'));
    }
}
