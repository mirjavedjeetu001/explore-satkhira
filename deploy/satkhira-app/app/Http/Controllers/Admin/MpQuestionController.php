<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MpQuestion;
use Illuminate\Http\Request;

class MpQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = MpQuestion::with(['mpProfile', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mp_profile_id')) {
            $query->where('mp_profile_id', $request->mp_profile_id);
        }

        $questions = $query->latest()->paginate(20);

        return view('admin.mp-questions.index', compact('questions'));
    }

    public function show(MpQuestion $mpQuestion)
    {
        $mpQuestion->load(['mpProfile', 'user']);
        return view('admin.mp-questions.show', compact('mpQuestion'));
    }

    public function edit(MpQuestion $mpQuestion)
    {
        $question = $mpQuestion->load(['mpProfile', 'user']);
        return view('admin.mp-questions.edit', compact('question'));
    }

    public function update(Request $request, MpQuestion $mpQuestion)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,answered,rejected',
            'answer' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];
        
        if (!empty($validated['answer'])) {
            $updateData['answer'] = $validated['answer'];
            $updateData['answered_at'] = now();
            $updateData['answered_by'] = auth()->id();
            if ($validated['status'] !== 'rejected') {
                $updateData['status'] = 'answered';
            }
        }

        $mpQuestion->update($updateData);

        return redirect()->route('admin.mp-questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function approve(MpQuestion $mpQuestion)
    {
        $mpQuestion->update(['status' => 'approved']);
        return back()->with('success', 'Question approved successfully.');
    }

    public function reject(MpQuestion $mpQuestion)
    {
        $mpQuestion->update(['status' => 'rejected']);
        return back()->with('success', 'Question rejected.');
    }

    public function answer(Request $request, MpQuestion $mpQuestion)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        $mpQuestion->update([
            'answer' => $validated['answer'],
            'status' => 'answered',
            'answered_at' => now(),
            'answered_by' => auth()->id(),
        ]);

        return back()->with('success', 'Question answered successfully.');
    }

    public function destroy(MpQuestion $mpQuestion)
    {
        $mpQuestion->delete();
        return redirect()->route('admin.mp-questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
