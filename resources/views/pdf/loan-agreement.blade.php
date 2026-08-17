<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1a2332; line-height: 1.6; }
        h1 { font-size: 20px; color: #1A2332; border-bottom: 2px solid #6DBE3B; padding-bottom: 10px; }
        .section { margin-top: 20px; }
        .label { color: #64748b; font-size: 11px; text-transform: uppercase; }
        .value { font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td { padding: 6px 0; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
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
            <tr>
                <td class="label">Date</td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="placeholder-notice">
        This is a placeholder agreement document. Full terms and conditions will be added here.
    </div>
</body>
</html>