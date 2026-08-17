<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Update</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <tr>
                    <td style="background:linear-gradient(135deg,#1A2332 0%,#243447 100%); padding:36px 40px; text-align:center;">
                        <h1 style="margin:0; color:#ffffff; font-size:22px; font-weight:800;">African Investment Partners</h1>
                        <p style="margin:8px 0 0; color:rgba(255,255,255,0.6); font-size:13px;">Fast & Transparent Loan Solutions</p>
                    </td>
                </tr>

                <!-- Red Banner -->
                <tr>
                    <td style="background:#ef4444; padding:20px 40px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:18px; font-weight:800;">Loan Application Update</h2>
                    </td>
                </tr>

                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 8px; color:#64748b; font-size:13px; text-transform:uppercase; font-weight:700;">Dear,</p>
                        <h2 style="margin:0 0 20px; color:#1a2332; font-size:20px; font-weight:800;">
                            {{ $application->customer->first_name }} {{ $application->customer->last_name }}
                        </h2>

                        <p style="margin:0 0 16px; color:#475569; font-size:15px; line-height:1.7;">
                            Thank you for applying to African Investment Partners. After careful review, we regret to inform you that your loan application <strong>({{ $application->application_no }})</strong> has been <strong style="color:#ef4444;">declined</strong> at this time.
                        </p>

                        <!-- Reason Box -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2; border-radius:12px; border:1px solid #fecaca; margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0 0 6px; color:#b91c1c; font-size:12px; font-weight:700; text-transform:uppercase;">Reason for Decline</p>
                                    <p style="margin:0; color:#7f1d1d; font-size:14px; line-height:1.6;">{{ $reason }}</p>
                                </td>
                            </tr>
                        </table>

                        <!-- Loan Summary -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px;">
                            <tr>
                                <td style="padding:20px 24px;">
                                    <p style="margin:0 0 12px; color:#1a2332; font-size:13px; font-weight:700; text-transform:uppercase;">Application Reference</p>
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Application No</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">{{ $application->application_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Amount Requested</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">₦{{ number_format($application->loan_amount, 0) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 16px; color:#475569; font-size:14px; line-height:1.7;">
                            You are welcome to reapply after 90 days or contact us to discuss alternative options. We value your interest in AIP and hope to serve you in the future.
                        </p>

                        <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.6;">
                            For questions, contact us at <a href="mailto:info@aiploan.com" style="color:#6DBE3B;">info@aiploan.com</a>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="background:#f8fafc; padding:24px 40px; border-top:1px solid #e2e8f0; text-align:center;">
                        <p style="margin:0 0 4px; color:#94a3b8; font-size:12px;">&copy; {{ date('Y') }} African Investment Partners. All rights reserved.</p>
                        <p style="margin:0; color:#cbd5e1; font-size:11px;">Lagos, Nigeria &bull; info@aiploan.com</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>