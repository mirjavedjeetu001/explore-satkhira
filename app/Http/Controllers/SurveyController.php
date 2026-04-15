<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyVote;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function show($id)
    {
        $survey = Survey::where('is_active', true)->findOrFail($id);
        $results = $survey->results;
        $totalVotes = $survey->votes()->count();
        $hasVoted = false;

        // Check by phone in session
        $votedSurveys = session('voted_surveys', []);
        if (in_array($id, $votedSurveys)) {
            $hasVoted = true;
        }

        return view('frontend.survey.show', compact('survey', 'results', 'totalVotes', 'hasVoted'));
    }

    public function vote(Request $request, $id)
    {
        $survey = Survey::where('is_active', true)->findOrFail($id);

        // Check if survey is live
        if (!$survey->is_live) {
            return back()->with('error', 'এই সার্ভে এখন চলছে না।');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]{11}$/',
            'selected_option' => 'required|string',
            'class_type' => 'required|in:intermediate,honours',
            'department' => 'required|string|max:255',
            'year' => 'required|string|max:100',
            'session' => 'nullable|string|max:100',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check duplicate by phone
        $exists = SurveyVote::where('survey_id', $id)
            ->where('phone', $request->phone)
            ->exists();

        if ($exists) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই সার্ভেতে ভোট দিয়েছেন!')->withInput();
        }

        // Check duplicate by device fingerprint
        $fingerprint = $request->input('device_fingerprint');
        if ($fingerprint) {
            $deviceExists = SurveyVote::where('survey_id', $id)
                ->where('device_fingerprint', $fingerprint)
                ->exists();
            if ($deviceExists) {
                return back()->with('error', 'এই ডিভাইস থেকে ইতিমধ্যে ভোট দেওয়া হয়েছে!')->withInput();
            }
        }

        SurveyVote::create([
            'survey_id' => $id,
            'name' => $request->name,
            'phone' => $request->phone,
            'class_type' => $request->class_type,
            'department' => $request->department,
            'year' => $request->year,
            'session' => $request->session,
            'selected_option' => $request->selected_option,
            'comment' => $request->comment,
            'device_fingerprint' => $fingerprint,
            'ip_address' => $request->ip(),
        ]);

        // Store in session
        $votedSurveys = session('voted_surveys', []);
        $votedSurveys[] = $id;
        session(['voted_surveys' => $votedSurveys]);

        return back()->with('success', 'আপনার ভোট সফলভাবে গৃহীত হয়েছে! ধন্যবাদ।');
    }

    public function results($id)
    {
        $survey = Survey::where('is_active', true)->findOrFail($id);
        $results = $survey->results;
        $totalVotes = $survey->votes()->count();

        return response()->json([
            'results' => $results,
            'totalVotes' => $totalVotes,
        ]);
    }
}
