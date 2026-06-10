<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran</title>
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
                            <div style="display:inline-block; background:#fef3c7; color:#92400e; font-size:12px; font-weight:bold; padding:5px 14px; border-radius:20px; letter-spacing:.5px;">
                                MENUNGGU PEMBAYARAN
                            </div>
                            <h1 style="font-size:22px; color:#2f5443; margin:16px 0 6px;">Booking kamu sudah kami terima ⏳</h1>
                            <p style="font-size:14px; color:#555; line-height:1.6; margin:0;">
                                Halo <strong>{{ $booking->guest->full_name }}</strong>, satu langkah lagi!
                                Selesaikan pembayaran agar jadwalmu langsung dikonfirmasi.
                            </p>
                        </td>
                    </tr>

                    {{-- Amount + expiry --}}
                    <tr>
                        <td style="padding:20px 32px 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef7f2; border:1px solid #d8e8df; border-radius:10px;">
                                <tr><td style="padding:16px 18px;" align="center">
                                    <div style="font-size:12px; color:#3d7a5a;">Total yang harus dibayar ({{ $transaction->payment_type }})</div>
                                    <div style="font-size:28px; font-weight:bold; color:#2f5443; margin:4px 0;">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</div>
                                    @if($transaction->expires_at)
                                        <div style="font-size:12px; color:#b45309;">Bayar sebelum <strong>{{ $transaction->expires_at->translatedFormat('d M Y, H:i') }} WIB</strong></div>
                                    @endif
                                </td></tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td style="padding:18px 32px 8px;" align="center">
                            <a href="{{ $payUrl }}"
                               style="display:inline-block; background:#2f5443; color:#ffffff; text-decoration:none; font-size:15px; font-weight:bold; padding:14px 34px; border-radius:999px;">
                                Lanjutkan Pembayaran
                            </a>
                        </td>
                    </tr>

                    {{-- Detail card --}}
                    <tr>
                        <td style="padding:16px 32px 8px;">
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
                                    </table>
                                </td></tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Tips --}}
                    <tr>
                        <td style="padding:8px 32px 28px;">
                            <p style="font-size:12px; color:#999; line-height:1.6; margin:0;">
                                Jika tombol di atas tidak berfungsi, salin tautan ini ke browser:<br>
                                <span style="color:#3d7a5a; word-break:break-all;">{{ $payUrl }}</span>
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
