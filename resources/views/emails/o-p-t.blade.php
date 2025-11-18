<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | OTP</title>
</head>
<body style="margin:0;background-color:#f5f5f7;font-family:'Segoe UI',Arial,sans-serif;color:#111;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fff;border-radius:12px;padding:32px;box-shadow:0 8px 24px rgba(15,23,42,.08);">
                <tr>
                    <td style="text-align:center;">
                        <h1 style="margin:0;font-size:20px;color:#0f172a;">{{ config('app.name') }}</h1>
                        <p style="margin:8px 0 24px;font-size:14px;color:#475467;">Secure One-Time Password</p>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;color:#0f172a;line-height:1.6;">
                        <p style="margin:0 0 16px;">Hi {{ $recipientName ?? (auth()->user()->name ?? 'there') }},</p>
                        <p style="margin:0 0 16px;">Use the verification code below to continue. It expires in 10 minutes.</p>
                        <p style="margin:0 0 24px;font-size:28px;font-weight:600;letter-spacing:4px;text-align:center;color:#2563eb;">
                            {{ $otp }}
                        </p>
                        <p style="margin:0 0 16px;">For your security, never share this code with anyone. If you did not request it, please ignore this email.</p>
                        <p style="margin:24px 0 0;">Regards,<br>{{ config('app.name') }} Team</p>
                    </td>
                </tr>
            </table>
            <p style="margin:24px 0 0;font-size:12px;color:#94a3b8;">© {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
        </td>
    </tr>
</table>
</body>
</html>
