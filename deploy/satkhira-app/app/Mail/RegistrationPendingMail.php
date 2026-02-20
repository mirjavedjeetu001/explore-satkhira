<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationPendingMail extends Mailable
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
            subject: 'রেজিস্ট্রেশন সম্পন্ন - অনুমোদনের অপেক্ষায় | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-pending',
        );
    }
}
