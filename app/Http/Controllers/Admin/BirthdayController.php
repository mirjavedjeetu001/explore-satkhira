<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\User;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::whereIn('role_id', function ($query) {
            $query->select('id')->from('roles')
                ->whereIn('slug', ['super-admin', 'admin', 'moderator']);
        })
            ->orWhere('is_upazila_moderator', true)
            ->with('birthdayCard')
            ->paginate(15);

        return view('admin.birthdays.index', compact('users'));
    }

    public function edit(User $user)
    {
        $birthdayCard = BirthdayCard::query()
            ->where('user_id', $user->id)
            ->whereDate('birthday_date', now()->toDateString())
            ->latest('id')
            ->first();

        return view('admin.birthdays.edit', compact('user', 'birthdayCard'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'date_of_birth' => 'nullable|date|before:today',
            'card_title' => 'nullable|string|max:120',
            'bengali_message' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ]);

        if (!empty($validated['card_title']) || !empty($validated['bengali_message'])) {
            $card = BirthdayCard::query()
                ->where('user_id', $user->id)
                ->whereDate('birthday_date', now()->toDateString())
                ->latest('id')
                ->first();

            if (!$card) {
                $card = new BirthdayCard();
                $card->user_id = $user->id;
                $card->birthday_date = now()->toDateString();
            }

            $card->english_message = $validated['card_title'] ?? $card->english_message;
            $card->bengali_message = $validated['bengali_message'] ?? $card->bengali_message;
            $card->save();
        }

        BirthdayCard::syncTodayForEligibleUsers();

        return redirect()->route('admin.birthdays.index')
            ->with('success', 'Birthday information updated successfully.');
    }

    public function todaysBirthdays()
    {
        BirthdayCard::syncTodayForEligibleUsers();

        $birthdays = BirthdayCard::query()
            ->forToday()
            ->forEligibleUsers()
            ->with('user', 'comments')
            ->get()
            ->unique('user_id')
            ->values();

        return view('admin.birthdays.todays', compact('birthdays'));
    }
}
