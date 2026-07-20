<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Course Baru - Queens English Prestige</title>
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
                                Admin Notification
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 30px;">
                            <h2 style="margin: 0 0 16px 0; color: #080D4D; font-size: 20px; font-weight: 700;">
                                Order Course Baru
                            </h2>

                            <p style="margin: 0 0 24px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                Halo Admin Queens English Prestige,<br><br>
                                Terdapat pemesanan course baru dari student yang perlu ditindaklanjuti. Berikut detail lengkap pesanan tersebut:
                            </p>

                            <!-- Order Detail Box -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600; width: 140px;">Kode Order</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 700;">: {{ $order->order_code }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Nama Student</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 700;">: {{ $student?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Email Student</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">: {{ $student?->email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">WhatsApp Student</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">: {{ $profile?->whatsapp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Program Course</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">: {{ $courseProgram?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Nama Course</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 700;">: {{ $courseLevel?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Biaya Course</td>
                                                <td style="padding: 6px 0; color: #080D4D; font-size: 15px; font-weight: 800;">: Rp {{ number_format((float) $order->price, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Tanggal Order</td>
                                                <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">: {{ $formattedOrderDate }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; font-weight: 600;">Status</td>
                                                <td style="padding: 6px 0; color: #d97706; font-size: 14px; font-weight: 700;">: Menunggu Konfirmasi Pembayaran</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <a href="{{ route('admin.orders.show', $order) }}" target="_blank" style="display: inline-block; background-color: #080D4D; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 10px; box-shadow: 0 2px 6px rgba(8, 13, 77, 0.25);">
                                    Open Order Detail
                                </a>
                            </div>

                            <!-- Text Link Fallback -->
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; text-align: center; line-height: 1.5; word-break: break-all;">
                                Jika tombol di atas tidak dapat diklik, salin dan buka tautan berikut di browser:<br>
                                <a href="{{ route('admin.orders.show', $order) }}" style="color: #2563eb; text-decoration: underline;">{{ route('admin.orders.show', $order) }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.5;">
                                Email ini dikirim otomatis oleh Sistem Queens English Prestige.<br>
                                Harap tidak membalas email notifikasi ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
