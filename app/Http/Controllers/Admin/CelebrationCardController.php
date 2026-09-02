<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CelebrationCardSetting;
use Illuminate\Http\Request;

class CelebrationCardController extends Controller
{
    public function index()
    {
        $settings = CelebrationCardSetting::getSettings();

        return view('admin.celebration-card.index', compact('settings'));
    }

    public function toggleStatus()
    {
        $settings = CelebrationCardSetting::getSettings();
        $settings->update(['is_enabled' => !$settings->is_enabled]);

        return back()->with(
            'success',
            $settings->is_enabled
                ? 'শুভেচ্ছা কার্ড ফিচার চালু হয়েছে।'
                : 'শুভেচ্ছা কার্ড ফিচার বন্ধ হয়েছে।'
        );
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'brand_name' => ['required', 'string', 'max:100'],
            'brand_tagline' => ['nullable', 'string', 'max:150'],
            'headline' => ['required', 'string', 'max:180'],
            'footer_text' => ['nullable', 'string', 'max:180'],
        ]);

        CelebrationCardSetting::getSettings()->update($validated);

        return back()->with('success', 'শুভেচ্ছা কার্ড সেটিংস আপডেট হয়েছে।');
    }
}
