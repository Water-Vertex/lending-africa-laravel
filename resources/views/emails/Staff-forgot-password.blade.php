<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f5;font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#6DBE3B;padding:24px 32px;">
                            <span style="color:#ffffff;font-size:20px;font-weight:bold;">AIP Staff Portal</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:16px;color:#1f2937;margin:0 0 16px;">Hi {{ $staffName }},</p>
                            <p style="font-size:14px;color:#4b5563;line-height:1.6;margin:0 0 24px;">
                                We received a request to reset the password for your Staff Portal account.
                                Click the button below to choose a new password.
                            </p>

                            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="border-radius:8px;background:#6DBE3B;">
                                        <a href="{{ $resetLink }}" style="display:inline-block;padding:12px 28px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;border-radius:8px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px;color:#6b7280;line-height:1.6;margin:0 0 8px;">
                                This link will expire in {{ $expiryMinutes }} minutes.
                            </p>
                            <p style="font-size:13px;color:#6b7280;line-height:1.6;margin:0;">
                                If you did not request a password reset, you can safely ignore this email —
                                your password will remain unchanged.
                            </p>

                            <p style="font-size:12px;color:#9ca3af;line-height:1.6;margin:24px 0 0;word-break:break-all;">
                                Or copy and paste this link into your browser:<br>
                                {{ $resetLink }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;background:#f9fafb;">
                            <p style="font-size:11px;color:#9ca3af;margin:0;">&copy; {{ date('Y') }} African Investment Partners (AIP). All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>