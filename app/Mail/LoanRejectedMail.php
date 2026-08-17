<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your Loan Application – African Investment Partners',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-rejected',
            with: [
                'application' => $this->application,
                'reason'      => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}