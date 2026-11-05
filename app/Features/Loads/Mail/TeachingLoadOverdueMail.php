<?php

namespace App\Features\Loads\Mail;

use App\Features\Loads\Models\Load;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeachingLoadOverdueMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Load $load, public ?string $recipientName = null)
    {
        $this->load->loadMissing(['faculties.department', 'subject', 'section']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Overdue Teaching Load Submission',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.teaching-loads.overdue',
            with: [
                'load' => $this->load,
                'recipientName' => $this->recipientName,
            ],
        );
    }
}
