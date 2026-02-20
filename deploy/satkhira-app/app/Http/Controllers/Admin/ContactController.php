<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function markRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);
        return back()->with('success', 'Marked as read.');
    }

    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        $contact->update([
            'reply' => $validated['reply'],
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => auth()->id(),
        ]);

        // Send email reply to the contact
        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact, $validated['reply']));
        } catch (\Exception $e) {
            \Log::error('Contact reply email failed: ' . $e->getMessage());
            return back()->with('warning', 'Reply saved but email could not be sent.');
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
