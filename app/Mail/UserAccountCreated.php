<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $userEmail;
    public $plainPassword;
    public $roleName;
    public $bankName;
    public $loginUrl;

    public function __construct($recipientName, $userEmail, $plainPassword, $roleName, $bankName = null, $loginUrl)
    {
        $this->recipientName = $recipientName;
        $this->userEmail     = $userEmail;
        $this->plainPassword = $plainPassword;
        $this->roleName      = $roleName;
        $this->bankName      = $bankName;
        $this->loginUrl      = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Your Account Has Been Created')
                    ->view('emails.useraccount');
    }
}