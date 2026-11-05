<?php

namespace App\Features\Loads\Mail;

use App\Features\Loads\Models\Load;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeachingLoadDeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Load $load, public ?string $recipientName = null)
    {
        $this->load->loadMissing(['faculties.department', 'subject', 'section']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Teaching Load Deadline Reminder (1 Week Left)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.teaching-loads.week-before',
            with: [
                'load' => $this->load,
                'recipientName' => $this->recipientName,
            ],
        );
    }
}
