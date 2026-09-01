<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $staffName;
    public string $resetLink;
    public int $expiryMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $staffName, string $resetLink, int $expiryMinutes = 15)
    {
        $this->staffName = $staffName;
        $this->resetLink = $resetLink;
        $this->expiryMinutes = $expiryMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Staff Portal Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.Staff-forgot-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}