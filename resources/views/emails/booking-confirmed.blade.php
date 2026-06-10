<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Dikonfirmasi</title>
</head>
<body style="margin:0; padding:0; background:#f4f3f0; font-family:Arial, Helvetica, sans-serif; color:#2b2b2b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f3f0; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:100%; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.06);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#2f5443; padding:28px 32px;">
                            <div style="font-size:22px; font-weight:bold; color:#ffffff; letter-spacing:1px;">UPFotoStudio</div>
                            <div style="font-size:12px; color:#cfe0d7; margin-top:4px;">Studio Foto &amp; Booking Online</div>
                        </td>
                    </tr>

                    {{-- Hero --}}
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <div style="display:inline-block; background:#dcfce7; color:#166534; font-size:12px; font-weight:bold; padding:5px 14px; border-radius:20px; letter-spacing:.5px;">
                                PEMBAYARAN BERHASIL
                            </div>
                            <h1 style="font-size:22px; color:#2f5443; margin:16px 0 6px;">Booking kamu sudah dikonfirmasi! 🎉</h1>
                            <p style="font-size:14px; color:#555; line-height:1.6; margin:0;">
                                Halo <strong>{{ $booking->guest->full_name }}</strong>, terima kasih sudah melakukan pembayaran.
                                Berikut ringkasan booking kamu. Invoice lengkap terlampir dalam bentuk PDF.
                            </p>
                        </td>
                    </tr>

                    {{-- Detail card --}}
                    <tr>
                        <td style="padding:20px 32px 8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f7f9f8; border:1px solid #e7ece9; border-radius:10px;">
                                <tr><td style="padding:14px 18px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#333;">
                                        <tr>
                                            <td style="padding:6px 0; color:#888; width:42%;">Kode Booking</td>
                                            <td style="padding:6px 0; font-weight:bold;">{{ $booking->booking_code }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#888;">Invoice</td>
                                            <td style="padding:6px 0; font-weight:bold;">{{ $transaction->invoice_number }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#888;">Studio</td>
                                            <td style="padding:6px 0;">{{ $booking->studio->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#888;">Paket</td>
                                            <td style="padding:6px 0;">{{ $booking->servicePackage->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#888;">Tanggal</td>
                                            <td style="padding:6px 0;">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0; color:#888;">Jam</td>
                                            <td style="padding:6px 0;">{{ \Illuminate\Support\Str::of($booking->start_time)->substr(0,5) }} – {{ \Illuminate\Support\Str::of($booking->end_time)->substr(0,5) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:10px 0 6px; color:#888; border-top:1px solid #e7ece9;">Dibayar ({{ $transaction->payment_type }})</td>
                                            <td style="padding:10px 0 6px; font-weight:bold; font-size:17px; color:#2f5443; border-top:1px solid #e7ece9;">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </td></tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td style="padding:18px 32px 28px;" align="center">
                            <a href="{{ route('frontend.booking.status', $transaction->invoice_number) }}"
                               style="display:inline-block; background:#2f5443; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; padding:13px 30px; border-radius:999px;">
                                Lihat Status Booking
                            </a>
                            <p style="font-size:12px; color:#999; line-height:1.6; margin:18px 0 0;">
                                Datang tepat waktu ya. Reschedule maks. 1x, 24 jam sebelum sesi.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f7f9f8; padding:18px 32px; border-top:1px solid #e7ece9;">
                            <p style="font-size:11px; color:#999; line-height:1.6; margin:0;">
                                Email ini dikirim otomatis oleh sistem UPFotoStudio.<br>
                                Surabaya, Indonesia &bull; hello@upfotostudio.test &bull; (+62) 812 0000 0000
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
