<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpazilaController extends Controller
{
    public function index(Request $request)
    {
        $query = Upazila::withCount(['listings' => fn($q) => $q->approved()]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%");
            });
        }

        $upazilas = $query->ordered()->paginate(15);

        return view('admin.upazilas.index', compact('upazilas'));
    }

    public function create()
    {
        return view('admin.upazilas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('upazilas', 'public');
        }

        Upazila::create($validated);

        return redirect()->route('admin.upazilas.index')
            ->with('success', 'Upazila created successfully.');
    }

    public function edit(Upazila $upazila)
    {
        return view('admin.upazilas.edit', compact('upazila'));
    }

    public function update(Request $request, Upazila $upazila)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('upazilas', 'public');
        }

        $upazila->update($validated);

        return redirect()->route('admin.upazilas.index')
            ->with('success', 'Upazila updated successfully.');
    }

    public function destroy(Upazila $upazila)
    {
        if ($upazila->listings()->count() > 0) {
            return back()->with('error', 'Cannot delete upazila with listings.');
        }

        $upazila->delete();
        return redirect()->route('admin.upazilas.index')
            ->with('success', 'Upazila deleted successfully.');
    }
}
