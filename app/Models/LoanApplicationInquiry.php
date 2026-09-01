<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplicationInquiry extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'loan_type',
        'loan_amount',
        'preferred_bank',
        'loan_purpose',
        'token',
        'email_sent',
        'email_sent_at',
        'email_verified_at',
        'status'
    ];
}