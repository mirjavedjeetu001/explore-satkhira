<?php

namespace App\Http\Controllers;

use App\Models\BirthdayCard;
use App\Models\BirthdayCardComment;
use Illuminate\Http\Request;

class BirthdayCardController extends Controller
{
    public function show($id)
    {
        $birthdayCard = BirthdayCard::with(['user.teamMember', 'comments'])
            ->findOrFail($id);

        return view('birthday-cards.show', compact('birthdayCard'));
    }

    public function storeComment(Request $request, $birthdayCardId)
    {
        $birthdayCard = BirthdayCard::findOrFail($birthdayCardId);

        // Check if phone number already commented
        $existingComment = BirthdayCardComment::where('birthday_card_id', $birthdayCardId)
            ->where('visitor_phone', $request->visitor_phone)
            ->first();

        if ($existingComment) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই কার্ডে মন্তব্য করেছেন। প্রতিটি ব্যক্তি শুধুমাত্র একবার মন্তব্য করতে পারেন।');
        }

        $validated = $request->validate([
            'visitor_name' => 'required|string|max:100',
            'visitor_phone' => 'required|string|unique:birthday_card_comments|regex:/^[0-9+\-\s()]*$/',
            'wish_message' => 'required|string|max:500',
        ], [
            'visitor_phone.unique' => 'এই ফোন নম্বর ইতিমধ্যে একটি মন্তব্য করেছে।',
        ]);

        BirthdayCardComment::create([
            'birthday_card_id' => $birthdayCardId,
            ...$validated,
        ]);

        return back()->with('success', 'আপনার শুভেচ্ছা সফলভাবে যোগ করা হয়েছে!');
    }

    public function downloadCard($id)
    {
        $birthdayCard = BirthdayCard::with('user')->findOrFail($id);

        // Generate image and download
        // For now, we'll return the card for display with download button in JS
        return response()->json([
            'user_name' => $birthdayCard->user->name,
            'message' => $birthdayCard->bengali_message,
            'card_image' => $birthdayCard->card_image,
        ]);
    }

    public function todaysBirthdays()
    {
        BirthdayCard::syncTodayForEligibleUsers();

        $birthdayCards = BirthdayCard::query()
            ->forToday()
            ->forEligibleUsers()
            ->with(['user', 'comments'])
            ->get()
            ->unique('user_id')
            ->values();

        return view('birthday-cards.todays', compact('birthdayCards'));
    }
}
