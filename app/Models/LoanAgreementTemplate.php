<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanAgreementTemplate extends Model
{
    protected $table = 'loan_agreement_templates';

    protected $fillable = [
        'loan_type',
        'title',
        'description',
    ];

    const LOAN_TYPES = ['sme', 'personal'];
}