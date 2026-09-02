<?php

namespace App\Http\Controllers;

use App\Models\CelebrationCardSetting;

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
}
