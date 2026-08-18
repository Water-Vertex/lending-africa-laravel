<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanAdditionalInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $additionalMessage  
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Additional Information Needed – AIP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-additional-info',
            with: [
                'application' => $this->application,
                'additionalMessage'     => $this->additionalMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}