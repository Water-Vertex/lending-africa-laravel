<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'name',
        'loan_type',
        'minimum_amount',
        'maximum_amount',
        'interest_rate',
        'processing_fee',
        'late_fee',
        'duration_months',
        'description',
        'status',
    ];

    protected $casts = [
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'interest_rate'  => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'late_fee'       => 'decimal:2',
    ];
}
