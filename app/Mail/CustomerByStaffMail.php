<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\LoanApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerByStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $loanApplication;

    public function __construct(Customer $customer, LoanApplication $loanApplication)
    {
        $this->customer = $customer;
        $this->loanApplication = $loanApplication;
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.customerloandetails', [
            'customer' => $this->customer,
            'loanApplication' => $this->loanApplication,
        ])->setPaper('a4');

        return $this->subject('Loan Application Submitted - ' . $this->loanApplication->application_no)
                    ->view('emails.customerloandetails')
                    ->attachData(
                        $pdf->output(),
                        'Loan-Application-' . $this->loanApplication->application_no . '.pdf',
                        ['mime' => 'application/pdf']
                    );
    }
}