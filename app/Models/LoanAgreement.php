<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanAgreement extends Model
{
    protected $fillable = [
        'loan_application_id',
        'customer_id',
        'agreement_token',
        'agreement_token_expires_at',
        'agreement_sent',
        'agreement_sent_at',
        'signed_file_path',
        'signed_file_original_name',
        'signed_file_type',
        'signed_submitted_at',
        'status',
    ];

    protected $casts = [
        'agreement_token_expires_at' => 'datetime',
        'agreement_sent_at'          => 'datetime',
        'signed_submitted_at'        => 'datetime',
        'agreement_sent'             => 'boolean',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isExpired(): bool
    {
        return $this->agreement_token_expires_at &&
               now()->isAfter($this->agreement_token_expires_at);
    }
}