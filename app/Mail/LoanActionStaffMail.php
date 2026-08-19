<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanActionStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public string $action,        // 'approved' | 'rejected' | 'additional_info_requested'
        public string $message = ''
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->action) {
            'approved'                  => 'Loan Application Approved – ' . $this->application->application_no,
            'rejected'                  => 'Loan Application Declined – ' . $this->application->application_no,
            'additional_info_requested' => 'Additional Info Requested – ' . $this->application->application_no,
            default                     => 'Loan Application Update – ' . $this->application->application_no,
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-action-staff',
            with: [
                'application' => $this->application,
                'action'      => $this->action,
                'message'     => $this->message,
            ],
        );
    }
}