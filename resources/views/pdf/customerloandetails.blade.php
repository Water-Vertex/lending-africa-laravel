<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h2, h3 { margin-bottom: 4px; color: #1a1a1a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 5px 8px; border: 1px solid #ddd; vertical-align: top; }
        .label { font-weight: bold; width: 35%; background: #f5f5f5; }
        .section-title {
            background: #1A2332; color: #fff; padding: 6px 10px;
            margin-top: 20px; margin-bottom: 8px; font-size: 13px;
        }
    </style>
</head>
<body>

    <h2>Loan Application Summary</h2>
    <p>Application No: <strong>{{ $loanApplication->application_no }}</strong></p>
    <p>Date: {{ $loanApplication->application_date }}</p>

    {{-- Customer details section --}}
    <div class="section-title">Customer Details</div>
    <table>
        <tr><td class="label">Customer Code</td><td>{{ $customer->customer_code }}</td></tr>
        <tr><td class="label">Full Name</td><td>{{ $customer->full_name }}</td></tr>
        <tr><td class="label">Customer Type</td><td>{{ ucfirst($customer->customer_type) }}</td></tr>
        <tr><td class="label">Date of Birth</td><td>{{ optional($customer->date_of_birth)->format('d-M-Y') }}</td></tr>
        <tr><td class="label">Gender</td><td>{{ ucfirst($customer->gender ?? '-') }}</td></tr>
        <tr><td class="label">National ID</td><td>{{ $customer->national_id ?? '-' }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $customer->email ?? '-' }}</td></tr>
        <tr><td class="label">Phone (Primary)</td><td>{{ $customer->phone_primary }}</td></tr>
        <tr><td class="label">Phone (Secondary)</td><td>{{ $customer->phone_secondary ?? '-' }}</td></tr>
        <tr><td class="label">Occupation</td><td>{{ $customer->occupation ?? '-' }}</td></tr>
        <tr><td class="label">Monthly Income</td><td>₦{{ number_format($customer->monthly_income ?? 0, 2) }}</td></tr>
        <tr><td class="label">Address</td>
            <td>
                {{ $customer->address ?? '-' }},
                {{ $customer->city ?? '' }}, {{ $customer->local_government_area ?? '' }},
                {{ $customer->state ?? '' }}, {{ $customer->country ?? '' }}
            </td>
        </tr>
    </table>

    {{-- Business details section (SME customers only) --}}
    @if($customer->customer_type === 'sme' && $loanApplication->business)
    <div class="section-title">Business Details</div>
    <table>
        <tr><td class="label">Business Name</td><td>{{ $loanApplication->business->business_name }}</td></tr>
        <tr><td class="label">Registration No.</td><td>{{ $loanApplication->business->registration_number ?? '-' }}</td></tr>
        <tr><td class="label">Tax Number</td><td>{{ $loanApplication->business->tax_number ?? '-' }}</td></tr>
        <tr><td class="label">Business Type</td><td>{{ $loanApplication->business->business_type ?? '-' }}</td></tr>
        <tr><td class="label">Monthly Revenue</td><td>₦{{ number_format($loanApplication->business->monthly_revenue ?? 0, 2) }}</td></tr>
        <tr><td class="label">Monthly Expense</td><td>₦{{ number_format($loanApplication->business->monthly_expense ?? 0, 2) }}</td></tr>
        <tr><td class="label">Address</td>
            <td>
                {{ $loanApplication->business->address ?? '-' }},
                {{ $loanApplication->business->city ?? '' }},
                {{ $loanApplication->business->local_government_area ?? '' }},
                {{ $loanApplication->business->state ?? '' }}
            </td>
        </tr>
    </table>
    @endif

    {{-- Bank account details section --}}
    @if($customer->bankAccounts && $customer->bankAccounts->count())
    <div class="section-title">Bank Account Details</div>
    <table>
        @foreach($customer->bankAccounts as $account)
        <tr><td class="label">Bank</td><td>{{ $account->bank->name ?? '-' }}</td></tr>
        <tr><td class="label">Account Name</td><td>{{ $account->account_name }}</td></tr>
        <tr><td class="label">Account Number</td><td>{{ $account->account_number }}</td></tr>
        @endforeach
    </table>
    @endif

    {{-- Loan application details section --}}
    <div class="section-title">Loan Application Details</div>
    <table>
        <tr><td class="label">Application No.</td><td>{{ $loanApplication->application_no }}</td></tr>
        <tr><td class="label">Loan Product</td><td>{{ $loanApplication->loanProduct->name ?? '-' }}</td></tr>
        <tr><td class="label">Loan Amount</td><td>₦{{ number_format($loanApplication->loan_amount, 2) }}</td></tr>
        <tr><td class="label">Duration (Months)</td><td>{{ $loanApplication->duration_months }}</td></tr>
        <tr><td class="label">Purpose</td><td>{{ $loanApplication->purpose }}</td></tr>
        <tr><td class="label">Status</td><td>{{ ucfirst($loanApplication->status) }}</td></tr>
        <tr><td class="label">Application Date</td><td>{{ $loanApplication->application_date }}</td></tr>
    </table>

    {{-- Co-signer details section --}}
    @if($loanApplication->coSigner)
    <div class="section-title">Co-Signer Details</div>
    <table>
        <tr><td class="label">Full Name</td>
            <td>{{ trim($loanApplication->coSigner->first_name . ' ' . $loanApplication->coSigner->middle_name . ' ' . $loanApplication->coSigner->last_name) }}</td>
        </tr>
        <tr><td class="label">Date of Birth</td><td>{{ optional($loanApplication->coSigner->date_of_birth)->format('d-M-Y') ?? '-' }}</td></tr>
        <tr><td class="label">Occupation</td><td>{{ $loanApplication->coSigner->occupation ?? '-' }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $loanApplication->coSigner->email ?? '-' }}</td></tr>
        <tr><td class="label">Phone (Primary)</td><td>{{ $loanApplication->coSigner->phone_primary }}</td></tr>
        <tr><td class="label">Phone (Secondary)</td><td>{{ $loanApplication->coSigner->phone_secondary ?? '-' }}</td></tr>
        <tr><td class="label">Relationship</td><td>{{ $loanApplication->coSigner->relationship ?? '-' }}</td></tr>
        <tr><td class="label">BVN</td><td>{{ $loanApplication->coSigner->bvn ?? '-' }}</td></tr>
        <tr><td class="label">Address</td>
            <td>
                {{ $loanApplication->coSigner->address ?? '-' }},
                {{ $loanApplication->coSigner->city ?? '' }},
                {{ $loanApplication->coSigner->state ?? '' }},
                {{ $loanApplication->coSigner->country ?? '' }}
            </td>
        </tr>
    </table>
    @endif

    {{-- Uploaded documents section --}}
    @if($customer->documents && $customer->documents->count())
    <div class="section-title">Uploaded Documents</div>
    <table>
        @foreach($customer->documents as $doc)
        <tr>
            <td class="label">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</td>
            <td>{{ ucfirst($doc->verification_status) }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- Loan Calculation / Amortization section --}}
@if($loanApplication->loanAmount)
<div class="section-title">Loan Calculation Breakdown</div>
<table>
    <tr><td class="label">Principal Amount</td><td>₦{{ number_format($loanApplication->loanAmount->loan_amount, 2) }}</td></tr>
    <tr><td class="label">Interest Rate (Annual)</td><td>{{ number_format($loanApplication->loanAmount->interest_rate, 2) }}%</td></tr>
    <tr><td class="label">Duration</td><td>{{ $loanApplication->loanAmount->duration_months }} months</td></tr>
    <tr><td class="label">Monthly Payment (EMI)</td><td>₦{{ number_format($loanApplication->loanAmount->monthly_payment, 2) }}</td></tr>
    <tr><td class="label">Total Payment</td><td>₦{{ number_format($loanApplication->loanAmount->total_payment, 2) }}</td></tr>
    <tr><td class="label">Total Interest</td><td>₦{{ number_format($loanApplication->loanAmount->total_interest, 2) }}</td></tr>
</table>
@endif

</body>
</html>