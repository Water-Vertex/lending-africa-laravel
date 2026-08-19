<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Update – Staff Notification</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#1A2332 0%,#243447 100%); padding:28px 40px;">
                        <p style="margin:0 0 4px; color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:0.08em;">Staff Portal Notification</p>
                        <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:800;">African Investment Partners</h1>
                    </td>
                </tr>

                {{-- Action Banner --}}
                @php
                    $bannerColor = match($action) {
                        'approved'                  => '#6DBE3B',
                        'rejected'                  => '#ef4444',
                        'additional_info_requested' => '#f59e0b',
                        default                     => '#6b7280',
                    };
                    $bannerIcon = match($action) {
                        'approved'                  => '✅',
                        'rejected'                  => '❌',
                        'additional_info_requested' => '📋',
                        default                     => 'ℹ️',
                    };
                    $bannerText = match($action) {
                        'approved'                  => 'Application Approved',
                        'rejected'                  => 'Application Declined',
                        'additional_info_requested' => 'Additional Information Requested',
                        default                     => 'Application Updated',
                    };
                @endphp
                <tr>
                    <td style="background:{{ $bannerColor }}; padding:16px 40px; text-align:center;">
                        <p style="margin:0; font-size:22px;">{{ $bannerIcon }}</p>
                        <h2 style="margin:4px 0 0; color:#ffffff; font-size:17px; font-weight:800;">{{ $bannerText }}</h2>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:36px 40px;">

                        {{-- Staff Notice --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0 0 6px; color:#64748b; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">Staff Notice</p>
                                    <p style="margin:0; color:#1a2332; font-size:14px; line-height:1.6;">
                                        This notification is regarding a customer whose loan application you registered on their behalf. The application status has been updated by the AIP admin team.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Customer Info --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px;">
                            <tr>
                                <td style="padding:20px 24px;">
                                    <p style="margin:0 0 12px; color:#1a2332; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">Customer & Application Details</p>
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Customer Name</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">
                                                {{ $application->customer->first_name }} {{ $application->customer->last_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Customer Phone</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">
                                                {{ $application->customer->phone_primary ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Application No</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">
                                                {{ $application->application_no }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Loan Amount</td>
                                            <td style="padding:6px 0; color:#6DBE3B; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0;">
                                                ₦{{ number_format($application->loan_amount, 0) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">Loan Product</td>
                                            <td style="padding:6px 0; color:#1a2332; font-size:13px; font-weight:600; text-align:right; border-top:1px solid #e2e8f0;">
                                                {{ $application->loanProduct->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#64748b; font-size:13px; border-top:1px solid #e2e8f0;">New Status</td>
                                            <td style="padding:6px 0; font-size:13px; font-weight:700; text-align:right; border-top:1px solid #e2e8f0; color:{{ $bannerColor }};">
                                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        {{-- Admin Message --}}
                        @if($message)
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb; border-radius:12px; border:1px solid #fde68a; margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0 0 6px; color:#92400e; font-size:12px; font-weight:700; text-transform:uppercase;">
                                        @if($action === 'approved') Message / Note
                                        @elseif($action === 'rejected') Reason for Decline
                                        @else Additional Info Required
                                        @endif
                                    </p>
                                    <p style="margin:0; color:#78350f; font-size:14px; line-height:1.7;">{{ $message }}</p>
                                </td>
                            </tr>
                        </table>
                        @endif

                        {{-- Action specific note --}}
                        @if($action === 'approved')
                        <p style="margin:0 0 16px; color:#475569; font-size:14px; line-height:1.7;">
                            Please inform your customer that their loan has been <strong style="color:#6DBE3B;">approved</strong>. They will receive a separate email with the loan agreement to sign. Disbursement will proceed after the agreement is signed.
                        </p>
                        @elseif($action === 'rejected')
                        <p style="margin:0 0 16px; color:#475569; font-size:14px; line-height:1.7;">
                            Please inform your customer that their loan application has been <strong style="color:#ef4444;">declined</strong>. The reason is listed above. They may reapply after 90 days or contact AIP to discuss alternative options.
                        </p>
                        @else
                        <p style="margin:0 0 16px; color:#475569; font-size:14px; line-height:1.7;">
                            Please follow up with your customer to provide the additional information or documents listed above. Their application will remain <strong>under review</strong> until the requested information is received.
                        </p>
                        @endif

                        <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.6;">
                            For queries, contact AIP admin at <a href="mailto:info@aiploan.com" style="color:#6DBE3B;">info@aiploan.com</a>
                        </p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8fafc; padding:20px 40px; border-top:1px solid #e2e8f0; text-align:center;">
                        <p style="margin:0 0 4px; color:#94a3b8; font-size:12px;">&copy; {{ date('Y') }} African Investment Partners. Staff Portal Notification.</p>
                        <p style="margin:0; color:#cbd5e1; font-size:11px;">Lagos, Nigeria &bull; info@aiploan.com</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>