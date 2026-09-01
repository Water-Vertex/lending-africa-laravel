<?php

namespace App\Services;

class LoanCalculator
{
    /**
     * @return array{monthly_payment: float, total_payment: float, total_interest: float}
     */
    public static function calculate(float $amount, int $months, float $annualRatePercent): array
    {
        if ($months <= 0) {
            return ['monthly_payment' => 0, 'total_payment' => 0, 'total_interest' => 0];
        }

        $monthlyRate = ($annualRatePercent / 100) / 12;

        if ($monthlyRate == 0) {
            $emi = $amount / $months;
        } else {
            $emi = ($amount * $monthlyRate * pow(1 + $monthlyRate, $months))
                 / (pow(1 + $monthlyRate, $months) - 1);
        }

        $total = $emi * $months;

        return [
            'monthly_payment' => round($emi, 2),
            'total_payment'   => round($total, 2),
            'total_interest'  => round($total - $amount, 2),
        ];
    }
}