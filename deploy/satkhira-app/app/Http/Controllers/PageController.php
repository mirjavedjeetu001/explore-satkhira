<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $settings = SiteSetting::where('group', 'about')->pluck('value', 'key');
        return view('frontend.pages.about', compact('settings'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($validated);

        return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
    }

    public function show(Page $page)
    {
        if (!$page->is_active) {
            abort(404);
        }

        return view('frontend.pages.show', compact('page'));
    }
}
