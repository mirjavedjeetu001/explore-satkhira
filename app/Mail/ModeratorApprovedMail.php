<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ModeratorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 অভিনন্দন! আপনি উপজেলা মডারেটর হিসেবে নিযুক্ত হয়েছেন | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.moderator-approved',
        );
    }
}
