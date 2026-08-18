<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1a2332; line-height: 1.6; }
        h1 { font-size: 20px; color: #1A2332; border-bottom: 2px solid #6DBE3B; padding-bottom: 10px; }
        .section { margin-top: 20px; }
        .section-heading { font-size: 14px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; padding-top: 15px; margin-bottom: 2px; }
        .sub-text { font-size: 11px; color: #166534; font-weight: 600; margin-bottom: 10px; }
        .label { color: #64748b; font-size: 11px; text-transform: uppercase; width: 40%; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td { padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        .amount-highlight { font-weight: bold; color: #15803d; }
        .placeholder-notice { background: #fffbeb; border: 1px solid #fde68a; padding: 12px; margin-top: 20px; font-size: 11px; color: #92400e; }
    </style>
</head>
<body>
    <h1>Loan Agreement</h1>
    <p>African Investment Partners</p>

    <div class="section">
        <table>
            <tr>
                <td class="label">Application No</td>
                <td>{{ $application->application_no }}</td>
            </tr>
            <tr>
                <td class="label">Customer Name</td>
                <td>{{ $application->customer->first_name }} {{ $application->customer->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Loan Amount</td>
                <td>₦{{ number_format($application->loan_amount, 0) }}</td>
            </tr>
            <tr>
                <td class="label">Duration</td>
                <td>{{ $application->duration_months }} Months</td>
            </tr>
            @if($loanAmount)
            <tr>
                <td class="label">Interest Rate</td>
                <td>{{ number_format($loanAmount->interest_rate, 0) }}% per annum</td>
            </tr>
            @endif
            <tr>
                <td class="label">Date</td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    @if($loanAmount)
    <div class="section">
        <div class="section-heading">Repayment Schedule & Breakdown</div>
        <div class="sub-text">Amount to be paid over {{ $application->duration_months }} Months duration</div>
        
        <table>
            <tr>
                <td class="label">Monthly Installment (Each Month)</td>
                <td class="amount-highlight">₦{{ number_format($loanAmount->monthly_payment, 0) }}</td>
            </tr>
            <tr>
                <td class="label">Total Interest</td>
                <td>₦{{ number_format($loanAmount->total_interest, 0) }}</td>
            </tr>
            <tr>
                <td class="label">Total Repayment</td>
                <td style="font-weight: bold;">₦{{ number_format($loanAmount->total_payment, 0) }}</td>
            </tr>
        </table>
    </div>
    @else
    <div class="placeholder-notice">
        Repayment breakdown not available for this application.
    </div>
    @endif
</body>
</html>