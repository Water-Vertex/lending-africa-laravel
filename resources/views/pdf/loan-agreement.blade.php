<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 40px 45px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1a2332;
            line-height: 1.7;
        }

        /* ===== LETTERHEAD ===== */
        .letterhead {
            border-bottom: 3px solid #15803d;
            padding-bottom: 14px;
            margin-bottom: 4px;
        }
        .letterhead table {
            width: 100%;
            border-collapse: collapse;
        }
        .letterhead td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1A2332;
            letter-spacing: 0.5px;
        }
        .company-tagline {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .doc-ref {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
        .doc-ref strong {
            color: #1a2332;
        }

        /* ===== DOCUMENT TITLE ===== */
        .doc-title-wrap {
            text-align: center;
            margin-top: 22px;
            margin-bottom: 22px;
        }
        .doc-title {
            font-size: 20px;
            font-weight: bold;
            color: #1A2332;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .doc-subtitle {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }
        .doc-divider {
            width: 60px;
            height: 2px;
            background-color: #15803d;
            margin: 10px auto 0 auto;
        }

        /* ===== SECTION HEADINGS ===== */
        .section {
            margin-top: 22px;
        }
        .section-number-heading {
            font-size: 12.5px;
            font-weight: bold;
            color: #ffffff;
            background-color: #1A2332;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 7px 12px;
            margin-bottom: 0;
        }
        .sub-text {
            font-size: 10.5px;
            color: #166534;
            font-weight: 600;
            padding: 8px 12px 0 12px;
            margin-bottom: 4px;
        }

        /* ===== DETAIL TABLE (bordered, boxed) ===== */
        .detail-box {
            border: 1px solid #e2e8f0;
            border-top: none;
        }
        table.detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.detail-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #eef1f5;
            font-size: 12px;
        }
        table.detail-table tr:last-child td {
            border-bottom: none;
        }
        table.detail-table tr:nth-child(even) {
            background-color: #fafbfc;
        }
        .label {
            color: #64748b;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            width: 42%;
        }
       .value {
    font-weight: bold;
    color: #1a2332;
}
        .amount-highlight {
            font-weight: bold;
            color: #15803d;
            font-size: 13px;
        }

        /* ===== OPENING STATEMENT ===== */
        .opening-statement {
            font-size: 12px;
            color: #334155;
            text-align: justify;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 12px 16px;
            background-color: #f8fafc;
            border-left: 3px solid #15803d;
        }
        .opening-statement strong {
            color: #1a2332;
        }

        /* ===== PLACEHOLDER NOTICE ===== */
        .placeholder-notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 12px 14px;
            margin-top: 0;
            font-size: 11px;
            color: #92400e;
        }

        /* ===== TERMS / AGREEMENT CONTENT ===== */
        .terms-box {
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 16px 16px 6px 16px;
            font-size: 11.5px;
            color: #334155;
            text-align: justify;
        }
        .terms-box h1,
        .terms-box h2,
        .terms-box h3 {
            color: #1A2332;
            font-size: 13px;
            margin: 10px 0 6px 0;
        }
        .terms-box p {
            margin: 6px 0;
        }
        .terms-box ul,
        .terms-box ol {
            margin: 8px 0 8px 0;
            padding-left: 22px;
        }
        .terms-box li {
            margin-bottom: 5px;
        }
        .terms-box strong {
            color: #1a2332;
        }

        /* ===== SIGNATURE BLOCK ===== */
        .signature-section {
            margin-top: 40px;
        }
        table.signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.signature-table td {
            border: none;
            width: 48%;
            vertical-align: top;
            padding: 0;
            font-size: 11px;
        }
        table.signature-table td.spacer {
            width: 4%;
        }
        .signature-line {
            border-top: 1px solid #1a2332;
            margin-top: 45px;
            padding-top: 6px;
        }
        .signature-role {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* ===== FOOTER NOTE ===== */
        .footer-note {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- ===== 1. LETTERHEAD ===== -->
    <div class="letterhead">
        <table>
            <tr>
                <td>
                    <div class="company-name">African Investment Partners</div>
                    <div class="company-tagline">Trusted Lending Solutions</div>
                </td>
                <td class="doc-ref">
                    Document Ref: <strong>{{ $application->application_no }}</strong><br>
                    Issued: {{ now()->format('F d, Y') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- ===== 2. DOCUMENT TITLE ===== -->
    <div class="doc-title-wrap">
        <p class="doc-title">Loan Agreement</p>
        <p class="doc-subtitle">Terms &amp; Conditions of Facility</p>
        <div class="doc-divider"></div>
    </div>

    <!-- ===== 3. AGREEMENT TERMS (PEHLE) ===== -->
    @if($agreement)
    <div class="section">
        <div class="section-number-heading">1. {{ $agreement->title }}</div>
        <div class="terms-box">
            {!! $agreement->description !!}
        </div>
    </div>
    @endif

    <!-- ===== 4. FACILITY DETAILS TABLE (PHIR) ===== -->
    <div class="section">
        <div class="section-number-heading">2. Facility Details</div>
        <div class="detail-box">
            <table class="detail-table">
                <tr>
                    <td class="label">Application No</td>
                    <td class="value">{{ $application->application_no }}</td>
                </tr>
                <tr>
                    <td class="label">Customer Name</td>
                    <td class="value">{{ $application->customer->first_name ?? 'N/A' }} {{ $application->customer->last_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Loan Amount</td>
                    <td class="value">&#8358;{{ number_format($application->loan_amount ?? 0, 0) }}</td>
                </tr>
                <tr>
                    <td class="label">Duration</td>
                    <td class="value">{{ $application->duration_months ?? 'N/A' }} Months</td>
                </tr>
                @if(isset($loanAmount))
                <tr>
                    <td class="label">Interest Rate</td>
                    <td class="value">{{ number_format($loanAmount->interest_rate ?? 0, 0) }}% per annum</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Agreement Date</td>
                    <td class="value">{{ now()->format('F d, Y') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ===== 5. REPAYMENT SCHEDULE ===== -->
    <div class="section">
        <div class="section-number-heading">3. Repayment Schedule &amp; Breakdown</div>
        @if(isset($loanAmount))
        <div class="detail-box">
            <div class="sub-text">Amount to be paid over {{ $application->duration_months ?? 0 }} Months duration</div>
            <table class="detail-table">
                <tr>
                    <td class="label">Monthly Installment (Each Month)</td>
                    <td class="amount-highlight">&#8358;{{ number_format($loanAmount->monthly_payment ?? 0, 0) }}</td>
                </tr>
                <tr>
                   <td class="label">Total Interest</td>

            <td class="value">&#8358;{{ number_format($loanAmount->total_interest, 0) }}</td>
                </tr>
                <tr>
                    <td class="label">Total Repayment</td>
                    <td class="value" style="font-weight: bold;">&#8358;{{ number_format($loanAmount->total_payment ?? 0, 0) }}</td>
                </tr>
            </table>
        </div>
        @else
        <div class="placeholder-notice">
            Repayment breakdown not available for this application.
        </div>
        @endif
    </div>

    <!-- ===== 6. OPENING STATEMENT (PHIR) ===== -->
    <div class="opening-statement">
        This Loan Agreement ("Agreement") is entered into on <strong>{{ now()->format('F d, Y') }}</strong>
        between <strong>African Investment Partners</strong> ("AIP" / "the Lender") and
        <strong>{{ $application->customer->first_name ?? 'N/A' }} {{ $application->customer->last_name ?? '' }}</strong>
        ("the Borrower"), in connection with Loan Application No.
        <strong>{{ $application->application_no }}</strong>. By accepting the facility described herein,
        the Borrower agrees to be bound by the terms and conditions set out in this document.
    </div>

    <!-- ===== 7. SIGNATURE BLOCK ===== -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">
                        {{ $application->customer->first_name ?? 'N/A' }} {{ $application->customer->last_name ?? '' }}
                    </div>
                    <div class="signature-role">Borrower's Signature</div>
                </td>
                <td class="spacer"></td>
                <td>
                    <div class="signature-line">
                        For African Investment Partners
                    </div>
                    <div class="signature-role">Authorized Signatory</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ===== 8. FOOTER ===== -->
    <div class="footer-note">
        This is a system-generated document issued by African Investment Partners in connection with
        Application No. {{ $application->application_no }}. This document is confidential and intended
        solely for the named Borrower.
    </div>

</body>
</html>