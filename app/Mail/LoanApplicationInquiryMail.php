<?php

namespace App\Mail;

use App\Models\LoanApplicationInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanApplicationInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanApplicationInquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Complete Your Loan Application – African Investment Partners',
        );
    }

   // LoanApplicationInquiryMail.php mein content() update karo:
public function content(): Content
{
    $applicationUrl = url('/customer-add');

    return new Content(
        view: 'emails.loan-application-inquiry',
        with: [
            'applicationUrl' => $applicationUrl,
        ],
    );
}
// public function content(): Content
// {
// $applicationUrl = url(route('customer.create', [], false));
//     return new Content(
//         view: 'emails.loan-application-inquiry',
//         with: [
//             'applicationUrl' => $applicationUrl,
//         ],
//     );
//    }
}