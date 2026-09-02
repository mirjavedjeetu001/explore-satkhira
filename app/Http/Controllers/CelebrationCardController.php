<?php

namespace App\Http\Controllers;

use App\Models\CelebrationCardGeneration;
use App\Models\CelebrationCardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CelebrationCardController extends Controller
{
    public function index()
    {
        $settings = CelebrationCardSetting::getSettings();

        if (!$settings->is_enabled) {
            return view('frontend.celebration-card.disabled', compact('settings'));
        }

        return view('frontend.celebration-card.index', compact('settings'));
    }

    public function storeGeneration(Request $request)
    {
        if (! CelebrationCardSetting::isEnabled()) {
            return response()->json(['message' => 'This feature is currently disabled.'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'download_format' => ['required', 'in:png,jpg'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('celebration-card/visitor-photos', 'public')
            : null;

        CelebrationCardGeneration::create([
            'name' => $validated['name'],
            'designation' => $validated['designation'] ?? null,
            'download_format' => $validated['download_format'],
            'photo_path' => $photoPath,
        ]);

        return response()->json(['success' => true]);
    }
}
