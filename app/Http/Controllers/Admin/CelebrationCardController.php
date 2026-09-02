<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CelebrationCardRecipient;
use App\Models\CelebrationCardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CelebrationCardController extends Controller
{
    public function index()
    {
        $settings = CelebrationCardSetting::getSettings();
        $recipients = CelebrationCardRecipient::latest()->get();

        return view('admin.celebration-card.index', compact('settings', 'recipients'));
    }

    public function storeRecipient(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_designation' => ['nullable', 'string', 'max:100'],
            'recipient_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        CelebrationCardRecipient::create([
            'name' => $validated['recipient_name'],
            'designation' => $validated['recipient_designation'] ?? null,
            'photo_path' => $request->file('recipient_photo')->store('celebration-card/recipients', 'public'),
        ]);

        return back()->with('success', 'Recipient card saved successfully.');
    }

    public function destroyRecipient(CelebrationCardRecipient $recipient)
    {
        if ($recipient->photo_path) {
            Storage::disk('public')->delete($recipient->photo_path);
        }

        $recipient->delete();

        return back()->with('success', 'Recipient card deleted successfully.');
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
            'template_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $settings = CelebrationCardSetting::getSettings();
        $templateImage = $request->file('template_image');
        unset($validated['template_image']);

        if ($templateImage) {
            $validated['template_image_path'] = $templateImage->store('celebration-card/templates', 'public');
        }

        $settings->update($validated);

        return back()->with('success', 'শুভেচ্ছা কার্ড সেটিংস আপডেট হয়েছে।');
    }
}
