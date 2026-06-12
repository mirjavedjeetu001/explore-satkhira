<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorldCupSetting;
use Illuminate\Http\Request;

class WorldCupController extends Controller
{
    public function settings()
    {
        $settings = [
            'is_enabled' => WorldCupSetting::get('is_enabled', '1'),
            'show_on_homepage' => WorldCupSetting::get('show_on_homepage', '1'),
            'show_floating_button' => WorldCupSetting::get('show_floating_button', '1'),
            'section_title' => WorldCupSetting::get('section_title', '⚽ FIFA World Cup 2026'),
            'section_subtitle' => WorldCupSetting::get('section_subtitle', 'United States · Mexico · Canada | বাংলাদেশ সময় অনুযায়ী'),
        ];

        return view('admin.world-cup.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        WorldCupSetting::set('is_enabled', $request->boolean('is_enabled') ? '1' : '0');
        WorldCupSetting::set('show_on_homepage', $request->boolean('show_on_homepage') ? '1' : '0');
        WorldCupSetting::set('show_floating_button', $request->boolean('show_floating_button') ? '1' : '0');
        WorldCupSetting::set('section_title', $request->input('section_title', '⚽ FIFA World Cup 2026'));
        WorldCupSetting::set('section_subtitle', $request->input('section_subtitle', 'United States · Mexico · Canada | বাংলাদেশ সময় অনুযায়ী'));

        return redirect()->route('admin.world-cup.settings')
            ->with('success', 'World Cup সেটিংস সফলভাবে আপডেট হয়েছে।');
    }

    public function toggleFeature()
    {
        $current = WorldCupSetting::get('is_enabled', '1');
        $newValue = $current === '1' ? '0' : '1';
        WorldCupSetting::set('is_enabled', $newValue);

        return response()->json([
            'success' => true,
            'enabled' => $newValue === '1',
            'message' => $newValue === '1' ? 'World Cup ফিচার চালু করা হয়েছে।' : 'World Cup ফিচার বন্ধ করা হয়েছে।',
        ]);
    }
}
