<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $staffName;
    public $staffCode;
    public $staffEmail;
    public $plainPassword;
    public $bankName;
    public $loginUrl;

    public function __construct($staffName, $staffCode, $staffEmail, $plainPassword, $bankName, $loginUrl)
    {
        $this->staffName     = $staffName;
        $this->staffCode     = $staffCode;
        $this->staffEmail    = $staffEmail;
        $this->plainPassword = $plainPassword;
        $this->bankName      = $bankName;
        $this->loginUrl      = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Your Staff Account Has Been Created')
                   ->view('emails.staffaccount');  
    }
}