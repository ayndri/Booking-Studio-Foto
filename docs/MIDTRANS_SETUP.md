# Integrasi Midtrans Snap (Sandbox) — UPFotoStudio

Dokumen ini menjelaskan integrasi payment gateway **Midtrans Snap** ke aplikasi UPFotoStudio.
Mode aktif saat ini: **Sandbox (Development)**.

## 1. Cara kerja singkat

```
Booking dibuat  ──►  BookingService.createBooking()
                       └─► PaymentGatewayInterface.createQrisPayment()
                            └─► MidtransSnapGateway → Snap API → redirect_url
Customer  ──►  redirect ke halaman pembayaran Midtrans (QRIS/VA/dll)
            ──►  bayar
            ──►  Finish Redirect URL (/payment/finish?order_id=INV-...)
                   └─► sinkron status via Midtrans Status API → halaman status booking
Server Midtrans  ──►  Webhook /api/payments/midtrans/notification  (status final, signature-verified)
```

Gateway dipilih lewat `.env` (`PAYMENT_GATEWAY`):
- `mock`     → `MockQrisGateway` (simulasi lokal, tanpa internet)
- `midtrans` → `MidtransSnapGateway` (sandbox/production)

## 2. Konfigurasi `.env`

```env
PAYMENT_GATEWAY=midtrans

MIDTRANS_MERCHANT_ID=your-merchant-id
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_EXPIRY_MINUTES=30
```

> Setelah mengubah `.env`, jalankan `php artisan config:clear`.
> **Jangan commit Server Key ke git.** `.env` sudah ada di `.gitignore`.

## 3. Setting di Dashboard Midtrans (Settings → Configuration)

Gunakan environment **Sandbox**. Base yang dipakai: `http://upfotostudio.test`.

| Field                        | Nilai                                                          |
|------------------------------|----------------------------------------------------------------|
| Payment Notification URL     | `http://upfotostudio.test/api/payments/midtrans/notification`  |
| Recurring Notification URL   | `http://upfotostudio.test/api/payments/midtrans/notification`  |
| Pay Account Notification URL | `http://upfotostudio.test/api/payments/midtrans/notification`  |
| Finish Redirect URL          | `http://upfotostudio.test/payment/finish`                      |
| Unfinish Redirect URL        | `http://upfotostudio.test/payment/unfinish`                    |
| Error Redirect URL           | `http://upfotostudio.test/payment/error`                       |

**Catatan domain lokal:** Redirect URL (finish/unfinish/error) jalan normal karena dibuka
di browser sendiri — status disinkronkan via Midtrans Status API saat customer kembali.
Webhook (Notification URL) **tidak akan sampai** ke `upfotostudio.test` karena domain ini
hanya ada di komputer lokal; untuk testing hal ini tidak masalah. Bila ingin webhook benar-benar
jalan, expose lewat ngrok (lihat bagian 5) dan ganti khusus 3 URL notification ke domain ngrok.

## 4. File yang terlibat

| File | Peran |
|------|-------|
| `config/services.php` | Blok `midtrans` + `payment_gateway` |
| `app/Services/Payments/MidtransSnapGateway.php` | Panggil Snap API & Status API |
| `app/Services/Payments/PaymentService.php` | Verifikasi signature + map status Midtrans |
| `app/Http/Controllers/Api/MidtransNotificationController.php` | Webhook notifikasi |
| `app/Http/Controllers/Frontend/PaymentRedirectController.php` | finish / unfinish / error |
| `app/Providers/AppServiceProvider.php` | Binding gateway via `PAYMENT_GATEWAY` |
| `routes/api.php`, `routes/web.php` | Route webhook & redirect |

`invoice_number` aplikasi dipakai sebagai **`order_id`** Midtrans, jadi pemetaan transaksi 1:1.

## 5. Testing webhook lokal dengan ngrok (opsional tapi disarankan)

```powershell
# 1. Jalankan app
php artisan serve --port=8080

# 2. Expose ke publik
ngrok http 8080

# 3. Pakai URL https ngrok untuk semua endpoint di Dashboard Midtrans (bagian 3)
```

Tanpa webhook, status tetap tersinkron saat customer kembali ke `/payment/finish`
(karena controller memanggil **Status API** Midtrans). Webhook hanya membuat update
lebih andal (mis. customer menutup tab sebelum redirect).

## 6. Cara mencoba (Sandbox)

1. `PAYMENT_GATEWAY=midtrans` di `.env`, lalu `php artisan config:clear`.
2. Buat booking seperti biasa → otomatis diarahkan ke halaman pembayaran Midtrans.
3. Pilih **QRIS** (atau metode lain). Untuk QRIS sandbox, klik tombol simulasi
   yang muncul, atau gunakan **Midtrans Simulator**:
   https://simulator.sandbox.midtrans.com/
4. Kartu kredit sandbox (jika test CC): `4811 1111 1111 1114`, CVV `123`, exp bebas (masa depan), OTP `112233`.
5. Setelah bayar → kembali ke halaman status booking, status berubah `SUCCESS`
   dan booking menjadi `CONFIRMED`.

## 7. Naik ke Production

1. Ganti key di `.env` dengan **Production** Server/Client Key.
2. `MIDTRANS_IS_PRODUCTION=true`.
3. Set URL endpoint di Dashboard Midtrans **Production** ke domain asli (`https://...`).
4. `php artisan config:clear` (atau `config:cache`).

## 8. Membatasi hanya ke QRIS (opsional)

Di `MidtransSnapGateway::createQrisPayment()` aktifkan baris:

```php
'enabled_payments' => ['other_qris', 'gopay', 'shopeepay'],
```
