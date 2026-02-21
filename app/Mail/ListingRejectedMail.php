<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Listing $listing;
    public string $reason;

    public function __construct(Listing $listing, string $reason)
    {
        $this->listing = $listing;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'আপনার তথ্য বাতিল হয়েছে | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.listing-rejected',
        );
    }
}
