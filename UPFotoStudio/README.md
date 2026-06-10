# Sistem Booking Ruang Photostudio Berbasis Web (Laravel 10)

Aplikasi ini dibuat dengan stack:
- Laravel 10
- MySQL
- Blade
- Bootstrap 5
- dompdf (invoice/laporan PDF)
- maatwebsite/excel (export Excel owner)

Fitur utama:
- Website publik untuk guest (tanpa login): beranda, tentang kami, galeri, paket harga, S&K, kontak, booking.
- Dashboard Admin: kelola studio, paket layanan, konten website, booking, transaksi, laporan PDF.
- Dashboard Owner: lihat laporan periodik (harian/mingguan/bulanan/tahunan/custom), export PDF dan Excel.
- Pembayaran QRIS realtime berbasis abstraction (`PaymentGatewayInterface`) dengan implementasi simulasi (`MockQrisGateway`).
- Validasi anti double booking (overlap) di server-side menggunakan database transaction.

## 1. Struktur Folder (rapi frontend vs backend)

```text
app/
  Contracts/
    Payments/
      PaymentGatewayInterface.php
  Exports/
    TransactionReportExport.php
  Http/
    Controllers/
      Frontend/
      Backend/
        Admin/
        Owner/
      Auth/
      Api/
    Middleware/
      RoleMiddleware.php
    Requests/
      Frontend/
      Backend/Admin/
      Auth/
  Models/
  Services/
    Bookings/
    Payments/
    Reports/

database/
  migrations/
  seeders/

resources/views/
  layouts/
    frontend.blade.php
    dashboard.blade.php
  frontend/
    booking/
  backend/
    admin/
    owner/
  auth/
  pdf/
    invoices/
    reports/
```

## 2. Entitas Database

Tabel utama yang dipakai:
- `users` (dengan role `admin` dan `owner`)
- `guests`
- `studios`
- `service_packages`
- `bookings`
- `payment_transactions`
- `website_contents` (tambahan untuk kelola konten halaman)

## 3. Instalasi

1. Install dependency
```bash
composer install
```

2. Copy env jika belum ada
```bash
cp .env.example .env
```

3. Set konfigurasi MySQL di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upfotostudio
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate app key
```bash
php artisan key:generate
```

5. Migrasi + seeder
```bash
php artisan migrate:fresh --seed
```

6. Jalankan server
```bash
php artisan serve
```

Akses:
- Website publik: `http://127.0.0.1:8000`
- Login dashboard: `http://127.0.0.1:8000/login`

## 4. Akun Demo

- Admin
  - Email: `admin@upfoto.test`
  - Password: `admin12345`
- Owner
  - Email: `owner@upfoto.test`
  - Password: `owner12345`

## 5. Alur Booking & Pembayaran

1. Guest isi form booking (`/booking`).
2. Sistem hitung `jam_selesai` otomatis dari `jam_mulai + durasi paket`.
3. Sistem validasi overlap booking pada studio yang sama untuk status `PENDING_PAYMENT` dan `CONFIRMED` (server-side + DB transaction).
4. Sistem hitung total biaya (harga paket + add-on jika ada).
5. Jika payment type = `DP`, nominal bayar dihitung:
   - `max(30% dari total, 50000)`
6. Sistem membuat `booking` + `payment_transactions` status `PENDING` dan QR payload dari `MockQrisGateway`.
7. Callback gateway mengubah status transaksi/booking.

## 6. Simulasi Callback QRIS (curl)

Endpoint callback:
```text
POST /api/payments/qris/callback
```

Contoh sukses:
```bash
curl -X POST http://127.0.0.1:8000/api/payments/qris/callback \
  -H "Content-Type: application/json" \
  -d '{"invoice_number":"INV-SAMPLE-0002","status":"SUCCESS"}'
```

Contoh gagal:
```bash
curl -X POST http://127.0.0.1:8000/api/payments/qris/callback \
  -H "Content-Type: application/json" \
  -d '{"invoice_number":"INV-SAMPLE-0002","status":"FAILED"}'
```

Contoh expired:
```bash
curl -X POST http://127.0.0.1:8000/api/payments/qris/callback \
  -H "Content-Type: application/json" \
  -d '{"invoice_number":"INV-SAMPLE-0002","status":"EXPIRED"}'
```

## 7. Generate Dokumen

- Invoice PDF guest:
  - `/booking/invoice/{invoice_number}`
- Laporan PDF admin:
  - Dashboard admin -> menu laporan -> tombol Export PDF
- Laporan PDF/Excel owner:
  - Dashboard owner -> menu laporan -> tombol Export PDF / Excel

## 8. Catatan Implementasi

- Role access control: middleware `role`.
- Validasi input: Form Request di `app/Http/Requests`.
- Service layer:
  - `BookingService` (proses booking + transaksi)
  - `PaymentService` (callback)
  - `ReportService` (laporan periodik)
- Payment gateway abstraction:
  - `PaymentGatewayInterface`
  - `MockQrisGateway`

## 9. Perintah bantu

Cek semua route:
```bash
php artisan route:list
```

Cek kode style (opsional):
```bash
./vendor/bin/pint
```
