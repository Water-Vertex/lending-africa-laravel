<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $adminMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Loan Application Has Been Approved – AIP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-approved',
            with: [
                'application'  => $this->application,
                'adminMessage' => $this->adminMessage,
            ],
        );
    }

    public function attachments(): array
    {
        // Loan amount (Monthly / Total / Interest) load karo agar already load nahi
        $loanAmount = $this->application->loanAmount
            ?? $this->application->loanAmount()->first();

        $pdf = Pdf::loadView('pdf.loan-agreement', [
            'application' => $this->application,
            'loanAmount'  => $loanAmount,
        ])->output();

        return [
            Attachment::fromData(fn () => $pdf, 'Loan-Agreement-' . $this->application->application_no . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}