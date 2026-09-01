<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\LoanCalculator;

class LoanAmount extends Model
{
    protected $fillable = [
        'loan_application_id',
        'customer_id',
        'loan_amount',
        'duration_months',
        'interest_rate',
        'monthly_payment',
        'total_payment',
        'total_interest',
        'status',
    ];

    protected $casts = [
        'loan_amount'     => 'decimal:2',
        'interest_rate'   => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'total_payment'   => 'decimal:2',
        'total_interest'  => 'decimal:2',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Create the amount breakdown for a given application + rate.
     */
    public static function createFor(LoanApplication $application, float $rate): self
    {
        $breakdown = LoanCalculator::calculate(
            (float) $application->loan_amount,
            (int) $application->duration_months,
            $rate
        );

        return self::create([
            'loan_application_id' => $application->id,
            'customer_id'         => $application->customer_id,
            'loan_amount'         => $application->loan_amount,
            'duration_months'     => $application->duration_months,
            'interest_rate'       => $rate,
            'monthly_payment'     => $breakdown['monthly_payment'],
            'total_payment'       => $breakdown['total_payment'],
            'total_interest'      => $breakdown['total_interest'],
            'status'              => $application->status,
        ]);
    }
}