<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MpProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MpProfileController extends Controller
{
    public function index()
    {
        $profiles = MpProfile::withCount('questions')->latest()->paginate(15);
        return view('admin.mp-profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('admin.mp-profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'elected_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('mp-profiles', 'public');
        }

        MpProfile::create($validated);

        return redirect()->route('admin.mp-profiles.index')
            ->with('success', 'MP Profile created successfully.');
    }

    public function edit(MpProfile $mpProfile)
    {
        return view('admin.mp-profiles.edit', compact('mpProfile'));
    }

    public function update(Request $request, MpProfile $mpProfile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'elected_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('mp-profiles', 'public');
        }

        $mpProfile->update($validated);

        return redirect()->route('admin.mp-profiles.index')
            ->with('success', 'MP Profile updated successfully.');
    }

    public function destroy(MpProfile $mpProfile)
    {
        $mpProfile->questions()->delete();
        $mpProfile->delete();
        return redirect()->route('admin.mp-profiles.index')
            ->with('success', 'MP Profile deleted successfully.');
    }
}
