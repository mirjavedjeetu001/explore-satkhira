<?php

namespace App\Http\Controllers;

use App\Models\MpProfile;
use App\Models\MpQuestion;
use Illuminate\Http\Request;

class MpController extends Controller
{
    public function index(Request $request)
    {
        $mpProfiles = MpProfile::active()->orderBy('constituency')->get();

        // Filter questions by selected MP if provided
        $query = MpQuestion::approved()->with(['user', 'mpProfile'])->latest();
        
        if ($request->filled('mp')) {
            $query->where('mp_profile_id', $request->mp);
        }

        $questions = $query->paginate(10);

        return view('frontend.mp.index', compact('mpProfiles', 'questions'));
    }

    public function askQuestion(Request $request)
    {
        $validated = $request->validate([
            'mp_profile_id' => 'required|exists:mp_profiles,id',
            'question' => 'required|string|max:2000',
        ], [
            'mp_profile_id.required' => 'সংসদ সদস্য নির্বাচন করুন',
            'question.required' => 'প্রশ্ন লিখুন',
        ]);

        MpQuestion::create([
            'mp_profile_id' => $validated['mp_profile_id'],
            'user_id' => auth()->id(),
            'question' => $validated['question'],
            'status' => 'pending',
            'is_public' => true,
        ]);

        return back()->with('success', 'আপনার প্রশ্ন সফলভাবে জমা দেওয়া হয়েছে। অনুমোদনের পর প্রকাশিত হবে।');
    }

    public function myQuestions()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $questions = MpQuestion::where('user_id', auth()->id())
            ->with('mpProfile')
            ->latest()
            ->paginate(10);

        return view('frontend.mp.my-questions', compact('questions'));
    }
}
