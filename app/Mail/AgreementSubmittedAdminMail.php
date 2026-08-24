<?php

namespace App\Mail;

use App\Models\LoanAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementSubmittedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanAgreement $agreement) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Signed Agreement Received – ' . $this->agreement->loanApplication->application_no,
        );
    }

    public function content(): Content
    {
        $adminUrl = config('app.admin_url', url('/'))
            . '/admin/loan-applications/'
            . $this->agreement->loan_application_id;

        return new Content(
            view: 'emails.agreement-submitted-admin',
            with: [
                'agreement' => $this->agreement,
                'adminUrl'  => $adminUrl,
            ],
        );
    }
}