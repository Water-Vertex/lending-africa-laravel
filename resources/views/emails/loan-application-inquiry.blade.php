<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Loan Application</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family: 'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding: 40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1A2332 0%, #243447 100%); padding: 36px 40px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:800; letter-spacing:-0.5px;">
                                African Investment Partners
                            </h1>
                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.6); font-size:13px;">
                                Fast & Transparent Loan Solutions
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px;">

                            <p style="margin:0 0 8px; color:#64748b; font-size:13px; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">
                                Hello,
                            </p>
                            <h2 style="margin:0 0 20px; color:#1a2332; font-size:22px; font-weight:800;">
                                {{ $inquiry->first_name }} {{ $inquiry->last_name }}
                            </h2>

                            <p style="margin:0 0 16px; color:#475569; font-size:15px; line-height:1.7;">
                                Thank you for submitting your loan inquiry to <strong>African Investment Partners</strong>. We have received your pre-application for a <strong>{{ ucfirst(str_replace('_', ' ', $inquiry->loan_type)) }}</strong> of <strong>₦{{ number_format($inquiry->loan_amount, 0) }}</strong>.
                            </p>

                            <p style="margin:0 0 28px; color:#475569; font-size:15px; line-height:1.7;">
                                To proceed, please complete your full application by clicking the button below. This will open a secure form where you can provide your complete details, KYC documents, and co-signer information.
                            </p>

                            {{-- CTA Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 8px 0 32px;">
                                        <a href="{{ $applicationUrl }}"
                                           style="display:inline-block; background: linear-gradient(135deg, #6DBE3B, #58A02E); color:#ffffff; text-decoration:none; padding: 16px 40px; border-radius:12px; font-size:15px; font-weight:700; letter-spacing:0.02em;">
                                            Complete My Application &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Info Box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border: 1px solid #e2e8f0; margin-bottom:28px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <p style="margin:0 0 12px; color:#1a2332; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">Your Inquiry Summary</p>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 6px 0; color:#64748b; font-size:13px;">Loan Type</td>
                                                <td style="padding: 6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right;">{{ ucfirst(str_replace('_', ' ', $inquiry->loan_type)) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Amount Requested</td>
                                                <td style="padding: 6px 0; color:#6DBE3B; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0;">₦{{ number_format($inquiry->loan_amount, 0) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Preferred Bank</td>
                                                <td style="padding: 6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ ucfirst($inquiry->preferred_bank) }} Bank</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 8px; color:#94a3b8; font-size:12px; line-height:1.6;">
                                If the button above does not work, copy and paste this link into your browser:
                            </p>
                            <p style="margin:0 0 28px; word-break:break-all;">
                                <a href="{{ $applicationUrl }}" style="color:#6DBE3B; font-size:12px;">{{ $applicationUrl }}</a>
                            </p>

                            <p style="margin:0; color:#475569; font-size:14px; line-height:1.7;">
                                If you did not submit this inquiry, please ignore this email or contact us at
                                <a href="mailto:info@aiploan.com" style="color:#6DBE3B;">info@aiploan.com</a>.
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0; text-align:center;">
                            <p style="margin:0 0 4px; color:#94a3b8; font-size:12px;">
                                &copy; {{ date('Y') }} African Investment Partners. All rights reserved.
                            </p>
                            <p style="margin:0; color:#cbd5e1; font-size:11px;">
                                Lagos, Nigeria &bull; info@aiploan.com
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>