<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanResubmittedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔄 Loan Application Resubmitted – ' . $this->application->application_no,
        );
    }

    public function content(): Content
    {
        $reviewUrl = config('app.admin_url', url('/')) . '/admin/loan-applications/' . $this->application->id;

        return new Content(
            view: 'emails.loan-resubmitted-admin',
            with: [
                'application' => $this->application,
                'reviewUrl'   => $reviewUrl,
            ],
        );
    }
}