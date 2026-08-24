<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signed Agreement Received</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <tr>
                    <td style="background:linear-gradient(135deg,#1A2332 0%,#243447 100%); padding:28px 40px;">
                        <p style="margin:0 0 4px; color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:0.08em;">Admin Notification</p>
                        <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:800;">African Investment Partners</h1>
                    </td>
                </tr>

                <tr>
                    <td style="background:#6DBE3B; padding:16px 40px; text-align:center;">
                        <p style="margin:0; font-size:24px;">✅</p>
                        <h2 style="margin:4px 0 0; color:#ffffff; font-size:17px; font-weight:800;">Signed Agreement Received</h2>
                        <p style="margin:4px 0 0; color:rgba(255,255,255,0.85); font-size:13px;">A customer has submitted their signed loan agreement</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:36px 40px;">

                        <p style="margin:0 0 20px; color:#475569; font-size:15px; line-height:1.7;">
                            The following customer has uploaded their signed loan agreement. Please login to the admin portal to review and download the signed document.
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px;">
                            <tr>
                                <td style="padding:20px 24px;">
                                    <p style="margin:0 0 12px; color:#1a2332; font-size:13px; font-weight:700; text-transform:uppercase;">Agreement Details</p>
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Application No</td>
                                            <td style="padding:7px 0; color:#1a2332; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0; font-family:monospace;">{{ $agreement->loanApplication->application_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Customer</td>
                                            <td style="padding:7px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ $agreement->loanApplication->customer->first_name }} {{ $agreement->loanApplication->customer->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Customer Phone</td>
                                            <td style="padding:7px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ $agreement->loanApplication->customer->phone_primary ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Loan Amount</td>
                                            <td style="padding:7px 0; color:#6DBE3B; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0;">₦{{ number_format($agreement->loanApplication->loan_amount, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">File Submitted</td>
                                            <td style="padding:7px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ $agreement->signed_file_original_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Submitted At</td>
                                            <td style="padding:7px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ $agreement->signed_submitted_at?->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $adminUrl }}"
                                       style="display:inline-block; background:linear-gradient(135deg,#6DBE3B,#58A02E); color:#ffffff; text-decoration:none; padding:14px 32px; border-radius:12px; font-size:14px; font-weight:700;">
                                        View Application & Download Agreement →
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0; color:#94a3b8; font-size:12px; text-align:center;">
                            Automated notification from AIP Loan Management System.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="background:#f8fafc; padding:20px 40px; border-top:1px solid #e2e8f0; text-align:center;">
                        <p style="margin:0; color:#94a3b8; font-size:12px;">&copy; {{ date('Y') }} African Investment Partners.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>