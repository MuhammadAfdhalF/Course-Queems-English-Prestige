<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Queens English Prestige</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: Arial, Helvetica, sans-serif; color: #334155; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #080D4D; padding: 32px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: 0.5px;">
                                Queens English Prestige
                            </h1>
                            <p style="margin: 6px 0 0 0; color: #f59e0b; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">
                                Account Security
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 30px;">
                            <h2 style="margin: 0 0 16px 0; color: #080D4D; font-size: 20px; font-weight: 700;">
                                Reset Password Request
                            </h2>

                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                Halo {{ $user->name ?? 'User' }},<br><br>
                                Kami menerima permintaan untuk mengatur ulang password akun Queens English Prestige Anda. Silakan klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetUrl }}" target="_blank" style="display: inline-block; background-color: #080D4D; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 10px; box-shadow: 0 4px 10px rgba(8, 13, 77, 0.25);">
                                    Reset Password
                                </a>
                            </div>

                            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 13px; line-height: 1.6;">
                                Link reset password ini berlaku selama <strong>{{ $expireMinutes }} menit</strong>. Jika Anda tidak meminta perubahan password, abaikan email ini dan password akun Anda tidak akan berubah.
                            </p>

                            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 14px 16px; margin-bottom: 24px;">
                                <p style="margin: 0; color: #b45309; font-size: 13px; font-weight: 600; line-height: 1.5;">
                                    ⚠️ <strong>Penting:</strong> Demi keamanan akun Anda, jangan pernah membagikan tautan ini kepada siapa pun.
                                </p>
                            </div>

                            <!-- Text Link Fallback -->
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; text-align: center; line-height: 1.5; word-break: break-all;">
                                Jika tombol di atas tidak dapat diklik, salin dan buka tautan berikut di browser Anda:<br>
                                <a href="{{ $resetUrl }}" style="color: #2563eb; text-decoration: underline;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.5;">
                                Email ini dikirim otomatis oleh Sistem Queens English Prestige.<br>
                                Harap tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
