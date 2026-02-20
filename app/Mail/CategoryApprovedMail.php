<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CategoryApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Collection $categories;

    public function __construct(User $user, Collection $categories)
    {
        $this->user = $user;
        $this->categories = $categories;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'আপনার ক্যাটাগরি অনুমোদিত হয়েছে! | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.category-approved',
        );
    }
}
