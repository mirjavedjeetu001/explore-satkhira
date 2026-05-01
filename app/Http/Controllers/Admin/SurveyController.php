<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::withCount('votes')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('admin.surveys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|max:5120',
            'has_comment_option' => 'nullable|boolean',
        ]);

        $data = $request->only('title', 'question', 'options', 'start_time', 'end_time');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_on_homepage'] = $request->boolean('show_on_homepage', true);
        $data['has_comment_option'] = $request->boolean('has_comment_option', false);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('surveys', 'public');
        }

        Survey::create($data);

        return redirect()->route('admin.surveys.index')->with('success', 'সার্ভে সফলভাবে তৈরি হয়েছে!');
    }

    public function show($id)
    {
        $survey = Survey::with('votes')->findOrFail($id);
        $results = $survey->results;
        $totalVotes = $survey->votes()->where('is_cancelled', false)->count();
        
        // Class type breakdown
        $classBreakdown = $survey->votes()->where('is_cancelled', false)->get()->groupBy('class_type')->map->count();
        
        return view('admin.surveys.show', compact('survey', 'results', 'totalVotes', 'classBreakdown'));
    }

    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        return view('admin.surveys.edit', compact('survey'));
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only('title', 'question', 'options', 'start_time', 'end_time');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['show_on_homepage'] = $request->boolean('show_on_homepage', true);
        $data['has_comment_option'] = $request->boolean('has_comment_option', false);

        if ($request->hasFile('image')) {
            if ($survey->image) {
                Storage::disk('public')->delete($survey->image);
            }
            $data['image'] = $request->file('image')->store('surveys', 'public');
        }

        $survey->update($data);

        return redirect()->route('admin.surveys.index')->with('success', 'সার্ভে আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        if ($survey->image) {
            Storage::disk('public')->delete($survey->image);
        }
        $survey->delete();

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->update(['is_active' => !$survey->is_active]);

        return back()->with('success', $survey->is_active ? 'সার্ভে সক্রিয় করা হয়েছে!' : 'সার্ভে নিষ্ক্রিয় করা হয়েছে!');
    }

    public function votes($id)
    {
        $survey = Survey::findOrFail($id);
        $votes = SurveyVote::where('survey_id', $id)->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.surveys.votes', compact('survey', 'votes'));
    }

    public function votesPdf($id)
    {
        $survey = Survey::findOrFail($id);
        $votes = SurveyVote::where('survey_id', $id)->orderBy('created_at', 'desc')->get();
        $results = $survey->results;
        $totalVotes = $votes->count();

        $pdf = Pdf::loadView('admin.surveys.votes-pdf', compact('survey', 'votes', 'results', 'totalVotes'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'survey-votes-' . $survey->id . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function votesExcel($id)
    {
        $survey = Survey::findOrFail($id);
        $votes = SurveyVote::where('survey_id', $id)->orderBy('created_at', 'desc')->get();
        $results = $survey->results;
        $totalVotes = $votes->count();

        $html = view('admin.surveys.votes-excel', compact('survey', 'votes', 'results', 'totalVotes'))->render();

        $filename = 'survey-votes-' . $survey->id . '-' . now()->format('Y-m-d') . '.xls';
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}
