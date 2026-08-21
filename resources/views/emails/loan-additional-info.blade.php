<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Required</title>
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

                <!-- Amber Banner -->
                <tr>
                    <td style="background:#f59e0b; padding:20px 40px; text-align:center;">
                        <p style="margin:0; font-size:24px;">📋</p>
                        <h2 style="margin:8px 0 0; color:#ffffff; font-size:18px; font-weight:800;">Action Required</h2>
                        <p style="margin:4px 0 0; color:rgba(255,255,255,0.85); font-size:13px;">Additional information needed for your application</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 8px; color:#64748b; font-size:13px; text-transform:uppercase; font-weight:700;">Dear,</p>
                        <h2 style="margin:0 0 20px; color:#1a2332; font-size:20px; font-weight:800;">
                            {{ $application->customer->first_name }} {{ $application->customer->last_name }}
                        </h2>

                        <p style="margin:0 0 16px; color:#475569; font-size:15px; line-height:1.7;">
                            Thank you for your loan application <strong>({{ $application->application_no }})</strong>. Our loan officer has reviewed your application and requires some additional information or documentation before we can proceed.
                        </p>

                        <!-- Message Box -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb; border-radius:12px; border:1px solid #fde68a; margin-bottom:24px;">
                            <tr>
                                <td style="padding:20px 24px;">
                                    <p style="margin:0 0 8px; color:#92400e; font-size:12px; font-weight:700; text-transform:uppercase;">Message from AIP Loan Officer</p>
                                    <p style="margin:0; color:#78350f; font-size:14px; line-height:1.7;">{{ $additionalMessage  }}</p>
                                </td>
                            </tr>
                        </table>
{{-- Edit Form CTA --}}
@if($editUrl)
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border-radius:12px; border:1px solid #bbf7d0; margin-bottom:24px;">
    <tr>
        <td style="padding:20px 24px;">
            <p style="margin:0 0 8px; color:#15803d; font-size:14px; font-weight:700;">
                🔗 Update Your Application
            </p>
            <p style="margin:0 0 16px; color:#166534; font-size:13px; line-height:1.6;">
                Click the button below to open your pre-filled application form. Your existing information will already be filled in — simply update what is needed and resubmit.
            </p>
            <p style="margin:0 0 8px; color:#166534; font-size:12px; font-weight:600;">
                ⚠️ This link is valid for 7 days only.
            </p>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <a href="{{ $editUrl }}"
                           style="display:inline-block; background:linear-gradient(135deg,#6DBE3B,#58A02E); color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:10px; font-size:14px; font-weight:700;">
                            Update My Application →
                        </a>
                    </td>
                </tr>
            </table>
            <p style="margin:12px 0 0; color:#94a3b8; font-size:11px; word-break:break-all;">
                Or copy this link: {{ $editUrl }}
            </p>
        </td>
    </tr>
</table>
@endif
                        <!-- Application Ref -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:5px 0; color:#64748b; font-size:13px;">Application No</td>
                                            <td style="padding:5px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right;">{{ $application->application_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Amount Requested</td>
                                            <td style="padding:5px 0; color:#6DBE3B; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0;">₦{{ number_format($application->loan_amount, 0) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 16px; color:#475569; font-size:14px; line-height:1.7;">
                            Please respond to this email or contact our loan officer as soon as possible. Delays in providing the requested information may affect the processing of your application.
                        </p>

                        <p style="margin:0; color:#94a3b8; font-size:12px;">
                            Contact: <a href="mailto:info@aiploan.com" style="color:#6DBE3B;">info@aiploan.com</a>
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