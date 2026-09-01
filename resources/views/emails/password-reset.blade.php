<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <tr>
                    <td style="background:linear-gradient(135deg,#1A2332 0%,#243447 100%); padding:28px 40px;">
                        <p style="margin:0 0 4px; color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:0.08em;">Admin Portal</p>
                        <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:800;">African Investment Partners</h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:36px 40px;">
                        <p style="margin:0 0 8px; color:#64748b; font-size:13px; font-weight:700; text-transform:uppercase;">Hello,</p>
                        <h2 style="margin:0 0 20px; color:#1a2332; font-size:20px; font-weight:800;">{{ $userName }}</h2>

                        <p style="margin:0 0 16px; color:#475569; font-size:15px; line-height:1.7;">
                            We received a request to reset your password for the AIP Admin Portal. Click the button below to reset it.
                        </p>

                        <p style="margin:0 0 8px; color:#ef4444; font-size:12px; font-weight:600;">
                            ⚠️ This link expires in <strong>10 minutes</strong>. If you did not request a password reset, please ignore this email.
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $resetUrl }}"
                                       style="display:inline-block; background:linear-gradient(135deg,#6DBE3B,#58A02E); color:#ffffff; text-decoration:none; padding:14px 32px; border-radius:12px; font-size:14px; font-weight:700;">
                                        Reset My Password →
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 8px; color:#94a3b8; font-size:12px;">
                            If the button does not work, copy and paste this link:
                        </p>
                        <p style="margin:0 0 20px; word-break:break-all;">
                            <a href="{{ $resetUrl }}" style="color:#6DBE3B; font-size:12px;">{{ $resetUrl }}</a>
                        </p>

                        <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.6;">
                            If you did not request this, no action is needed. Your password will remain unchanged.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="background:#f8fafc; padding:20px 40px; border-top:1px solid #e2e8f0; text-align:center;">
                        <p style="margin:0; color:#94a3b8; font-size:12px;">&copy; {{ date('Y') }} African Investment Partners. All rights reserved.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>