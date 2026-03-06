<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Collection $products)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Launchory Weekly Digest — Top Products This Week',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-digest',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
