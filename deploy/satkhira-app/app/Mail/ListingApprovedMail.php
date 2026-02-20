<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Listing $listing;

    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'আপনার তথ্য অনুমোদিত হয়েছে! | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.listing-approved',
        );
    }
}
