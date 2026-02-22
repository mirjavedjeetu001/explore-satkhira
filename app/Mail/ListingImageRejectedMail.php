<?php

namespace App\Mail;

use App\Models\ListingImage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingImageRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ListingImage $listingImage;
    public string $reason;

    public function __construct(ListingImage $listingImage, string $reason)
    {
        $this->listingImage = $listingImage;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'আপনার বিজ্ঞাপন/ছবি বাতিল হয়েছে | Explore Satkhira',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.listing-image-rejected',
        );
    }
}
