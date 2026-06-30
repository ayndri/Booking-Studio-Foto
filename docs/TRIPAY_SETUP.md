# Setup Pembayaran Tripay (QRIS)

Panduan mengaktifkan pembayaran QRIS via [Tripay](https://tripay.co.id) di UPFotoStudio.

## 1. Ambil kredensial

Dashboard Tripay → **Merchant** (pilih sandbox dulu untuk uji coba):

- **API Key**
- **Private Key**
- **Kode Merchant** (mis. `T1234`)

## 2. Isi `.env`

```env
PAYMENT_GATEWAY=tripay

TRIPAY_API_KEY=xxxxxxxx
TRIPAY_PRIVATE_KEY=xxxxxxxx
TRIPAY_MERCHANT_CODE=T1234
TRIPAY_IS_PRODUCTION=false      # true untuk produksi
TRIPAY_QRIS_METHOD=QRIS         # kode channel QRIS
TRIPAY_EXPIRY_MINUTES=30
```

Setelah ubah `.env` di server, jalankan:

```bash
php artisan config:clear && php artisan optimize
```

## 3. Pasang Callback URL di Tripay

Dashboard Tripay → **Merchant → Callback URL**:

```
https://domainkamu.com/api/payments/tripay/callback
```

Tripay mengirim webhook (header `X-Callback-Signature` = HMAC-SHA256 atas body, kunci = Private Key). Signature diverifikasi otomatis di `PaymentService::processTripayCallback`.

## 4. Return URL

Sudah otomatis di-set oleh aplikasi ke:

```
https://domainkamu.com/payment/tripay/finish
```

Saat customer kembali, aplikasi menyinkronkan status via Transaction Detail API (berguna bila webhook telat sampai).

## Alur singkat

1. Customer booking → aplikasi membuat transaksi QRIS di Tripay → diarahkan ke **checkout_url** Tripay (menampilkan QR QRIS + countdown).
2. Customer bayar → Tripay kirim **callback** → status transaksi & booking diperbarui (`SUCCESS`/`EXPIRED`/`FAILED`), email konfirmasi terkirim.
3. Transaksi `EXPIRED`/`FAILED` bisa dibuatkan QR baru lewat tombol **"Buat QR Baru & Bayar"** di halaman status.

## Status mapping

| Status Tripay | Status internal |
|---------------|-----------------|
| `PAID`        | SUCCESS         |
| `EXPIRED`     | EXPIRED         |
| `FAILED`, `REFUND` | FAILED     |
| `UNPAID`      | tetap PENDING   |

## Catatan

- Sandbox memakai base URL `https://tripay.co.id/api-sandbox`, produksi `https://tripay.co.id/api` (otomatis dari `TRIPAY_IS_PRODUCTION`).
- Webhook butuh URL publik (tidak jalan di `localhost`). Saat dev lokal, andalkan sinkronisasi di Return URL atau gunakan `PAYMENT_GATEWAY=mock`.
- Ganti gateway kapan saja via `PAYMENT_GATEWAY` (`mock` / `midtrans` / `tripay`) tanpa ubah kode.
