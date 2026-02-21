<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Contact;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        $settings = SiteSetting::where('group', 'about')->pluck('value', 'key');
        
        // Get upazila moderators first
        $upazilaModerators = User::where('is_upazila_moderator', true)
            ->where('status', 'active')
            ->with('upazila')
            ->orderBy('name')
            ->get();
        
        // Get team members, but exclude those who are upazila moderators
        $upazilaModeratorsUserIds = $upazilaModerators->pluck('id')->toArray();
        
        $teamMembers = TeamMember::with('user')
            ->active()
            ->ordered()
            ->whereHas('user', function($q) use ($upazilaModeratorsUserIds) {
                $q->whereNotIn('id', $upazilaModeratorsUserIds);
            })
            ->get();
        
        return view('frontend.pages.about', compact('settings', 'teamMembers', 'upazilaModerators'));
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

        $contact = Contact::create($validated);

        // Send email notification to admin
        try {
            $adminEmail = SiteSetting::get('contact_email') ?? 'info@exploresatkhira.com';
            Mail::to($adminEmail)->send(new ContactFormMail($contact));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Contact form email failed: ' . $e->getMessage());
        }

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
