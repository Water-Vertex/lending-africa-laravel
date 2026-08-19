<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>African Investment Partners – Staff Account</title>
  <style>
    /* Brand colors */
    :root {
      --color-primary: #6DBE3B;
      --color-primary-dark: #58A02E;
      --color-primary-light: #E8F5DE;
      --color-primary-xlight: #F4FAF0;
      --color-dark: #1A2332;
    }
    /* Reset & base */
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f6f8;
      font-family: Arial, Helvetica, sans-serif;
    }
    .email-wrapper {
      background-color: #f4f6f8;
      padding: 30px 0;
    }
    .email-container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    /* header – dark with primary accent */
    .header {
      background-color: #1A2332; /* --color-dark */
      padding: 24px 32px;
      border-bottom: 4px solid #6DBE3B; /* --color-primary */
    }
    .header h1 {
      color: #ffffff;
      font-size: 22px;
      font-weight: 600;
      margin: 0;
      letter-spacing: -0.3px;
    }
    .header h1 span {
      color: #6DBE3B;
    }
    /* body */
    .body-content {
      padding: 32px 32px 20px;
    }
    .body-content h2 {
      color: #1A2332;
      font-size: 20px;
      font-weight: 600;
      margin-top: 0;
      margin-bottom: 16px;
    }
    .body-content p {
      color: #2d3a4f;
      font-size: 15px;
      line-height: 1.6;
      margin: 0 0 16px;
    }
    .body-content p strong {
      color: #1A2332;
    }
    /* credentials card – light green background */
    .credentials-card {
      background-color: #F4FAF0; /* --color-primary-xlight */
      border: 1px solid #E8F5DE; /* --color-primary-light */
      border-radius: 10px;
      margin: 24px 0;
      padding: 18px 22px;
    }
    .credentials-card .field {
      margin-bottom: 14px;
    }
    .credentials-card .field:last-child {
      margin-bottom: 0;
    }
    .credentials-card .label {
      font-size: 13px;
      font-weight: 600;
      color: #4b5a6e;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 2px;
    }
    .credentials-card .value {
      font-size: 18px;
      font-weight: 600;
      color: #1A2332;
      word-break: break-word;
    }
    .credentials-card .value.password {
      font-family: 'Courier New', monospace;
      letter-spacing: 0.5px;
      background: #ffffff;
      display: inline-block;
      padding: 2px 12px;
      border-radius: 20px;
      border: 1px solid #dde9d0;
    }
    .btn-primary {
      display: inline-block;
      background-color: #6DBE3B;
      color: #ffffff;
      font-weight: 600;
      font-size: 15px;
      text-decoration: none;
      padding: 12px 32px;
      border-radius: 60px;
      transition: background-color 0.2s;
      border: 1px solid #58A02E;
      box-shadow: 0 2px 4px rgba(109, 190, 59, 0.2);
    }
    .btn-primary:hover {
      background-color: #58A02E;
    }
    .btn-wrapper {
      margin: 28px 0 16px;
    }
    .footer-note {
      font-size: 13px;
      color: #6f7d91;
      margin-top: 20px;
      border-top: 1px solid #eaeef3;
      padding-top: 18px;
    }
    .footer-note a {
      color: #6DBE3B;
      text-decoration: none;
    }
    .footer-note a:hover {
      text-decoration: underline;
    }
    /* footer */
    .email-footer {
      background-color: #f9fafc;
      padding: 14px 32px;
      text-align: center;
      border-top: 1px solid #eaeef3;
    }
    .email-footer p {
      color: #9aa8b9;
      font-size: 12px;
      margin: 0;
    }
    /* small screens */
    @media only screen and (max-width: 600px) {
      .email-container {
        width: 100% !important;
        border-radius: 0;
      }
      .header {
        padding: 20px 24px;
      }
      .body-content {
        padding: 24px 20px;
      }
      .credentials-card {
        padding: 16px;
      }
    }
  </style>
</head>
<body>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="email-wrapper" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
      <td align="center">
        <!-- main container -->
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="email-container" style="width:100%; max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.06);">
          <!-- Header -->
          <tr>
            <td class="header" style="background-color:#1A2332; padding:24px 32px; border-bottom:4px solid #6DBE3B;">
              <h1 style="color:#ffffff; font-size:22px; margin:0; font-weight:600; letter-spacing:-0.3px;">
                African <span style="color:#6DBE3B;">Investment</span> Partners
              </h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="body-content" style="padding:32px 32px 20px;">
              <h2 style="color:#1A2332; font-size:20px; font-weight:600; margin-top:0; margin-bottom:16px;">Your staff account is ready</h2>

              <p style="color:#2d3a4f; font-size:15px; line-height:1.6; margin:0 0 16px;">
                Hi <strong style="color:#1A2332;">{{ $staffName }}</strong>,
              </p>

              <p style="color:#2d3a4f; font-size:15px; line-height:1.6; margin:0 0 16px;">
                A staff account has been created for you on the 
                <strong style="color:#1A2332;">African Investment Partners</strong> Staff Portal
                
                Use the credentials below to log in.
              </p>

           <!-- Credentials card – Staff Code & Password with identical styling -->
              <div class="credentials-card" style="background-color:#F4FAF0; border:1px solid #E8F5DE; border-radius:10px; margin:24px 0; padding:18px 22px;">
                <div class="field" style="margin-bottom:14px;">
                  <div class="label" style="font-size:13px; font-weight:600; color:#4b5a6e; text-transform:uppercase; letter-spacing:0.3px; margin-bottom:2px;">Staff Code</div>
                  <div class="value" style="font-size:18px; font-weight:600; color:#1A2332; font-family:'Courier New',monospace; letter-spacing:0.5px; background:#ffffff; display:inline-block; padding:2px 14px; border-radius:20px; border:1px solid #dde9d0; word-break:break-word;">{{ $staffCode }}</div>
                </div>
                <div class="field" style="margin-bottom:0;">
                  <div class="label" style="font-size:13px; font-weight:600; color:#4b5a6e; text-transform:uppercase; letter-spacing:0.3px; margin-bottom:2px;">Password</div>
                  <div class="value" style="font-size:18px; font-weight:600; color:#1A2332; font-family:'Courier New',monospace; letter-spacing:0.5px; background:#ffffff; display:inline-block; padding:2px 14px; border-radius:20px; border:1px solid #dde9d0; word-break:break-word;">{{ $plainPassword }}</div>
                </div>
              </div>
              <!-- end credentials card -->

              <p style="color:#2d3a4f; font-size:15px; line-height:1.6; margin:0 0 16px;">
                For security, please log in and change your password after your first access.
              </p>

              <!-- Login button -->
              <div class="btn-wrapper" style="margin:28px 0 16px;">
                <a href="{{ $loginUrl }}" target="_blank" class="btn-primary" style="display:inline-block; background-color:#6DBE3B; color:#ffffff; font-weight:600; font-size:15px; text-decoration:none; padding:12px 32px; border-radius:60px; border:1px solid #58A02E; box-shadow:0 2px 4px rgba(109,190,59,0.2);">
                  Go to Staff Login
                </a>
              </div>

              <!-- small note -->
              <div class="footer-note" style="font-size:13px; color:#6f7d91; margin-top:20px; border-top:1px solid #eaeef3; padding-top:18px;">
                <p style="margin:0 0 4px; font-size:13px; color:#6f7d91;">
                  If you did not expect this email, please contact your administrator.
                </p>
                <p style="margin:8px 0 0; font-size:13px; color:#6f7d91;">
                  <span style="color:#1A2332; font-weight:500;">African Investment Partners</span> — secure loan management.
                </p>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td class="email-footer" style="background-color:#f9fafc; padding:14px 32px; text-align:center; border-top:1px solid #eaeef3;">
              <p style="color:#9aa8b9; font-size:12px; margin:0;">
                &copy; {{ date('Y') }} African Investment Partners. All rights reserved.
              </p>
            </td>
          </tr>
        </table>
        <!-- end container -->
      </td>
    </tr>
  </table>
</body>
</html>