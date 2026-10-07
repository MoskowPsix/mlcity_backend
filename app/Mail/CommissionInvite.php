<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommissionInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $eventName, public string $url, public bool $pending)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Вам открыли комиссию мероприятия');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.view.commissionInvite');
    }
}
