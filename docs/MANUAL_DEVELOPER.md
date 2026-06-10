# Manual Book Developer — UPFotoStudio

Dokumentasi teknis untuk developer yang mengembangkan & memelihara aplikasi **UPFotoStudio**.

---

## Daftar Isi

1. [Ringkasan & Tech Stack](#1-ringkasan--tech-stack)
2. [Persyaratan Sistem](#2-persyaratan-sistem)
3. [Instalasi & Setup Lokal](#3-instalasi--setup-lokal)
4. [Konfigurasi Environment (.env)](#4-konfigurasi-environment-env)
5. [Struktur Proyek](#5-struktur-proyek)
6. [Arsitektur Aplikasi](#6-arsitektur-aplikasi)
7. [Model & Skema Database](#7-model--skema-database)
8. [Routing](#8-routing)
9. [Autentikasi & Otorisasi](#9-autentikasi--otorisasi)
10. [Alur Booking](#10-alur-booking)
11. [Payment Gateway](#11-payment-gateway)
12. [Laporan & Ekspor](#12-laporan--ekspor)
13. [Email](#13-email)
14. [Perintah Penting](#14-perintah-penting)
15. [Troubleshooting (Laragon/Windows)](#15-troubleshooting-laragonwindows)

---

## 1. Ringkasan & Tech Stack

UPFotoStudio adalah web app **Laravel 10** untuk booking sewa studio foto dengan pembayaran QRIS. Memiliki 3 peran: **tamu (frontend publik)**, **admin**, dan **owner**.

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 10 |
| Bahasa | PHP 8.2 (Laragon) — `composer.json` minimum `^8.1` |
| Database | MySQL 8 (`db_upfotostudio`) |
| Frontend build | Vite + Bootstrap 5 |
| PDF | `barryvdh/laravel-dompdf` |
| Excel | `maatwebsite/excel` |
| Auth helper | `laravel/sanctum` |
| Payment | Pluggable: `mock` / `midtrans` (Snap) |

---

## 2. Persyaratan Sistem

- PHP 8.2+ dengan ekstensi standar Laravel (mbstring, openssl, pdo_mysql, gd, dll.)
- Composer
- Node.js + npm
- MySQL 8
- (Disarankan) Laragon di Windows

---

## 3. Instalasi & Setup Lokal

```powershell
# 1. Dependencies PHP
#    (gunakan --ignore-platform-reqs karena composer.lock dibuat di PHP 8.4,
#     sedangkan environment lokal berjalan di PHP 8.2)
composer install --ignore-platform-reqs

# 2. Dependencies JS
npm install

# 3. Siapkan .env
copy .env.example .env
php artisan key:generate

# 4. Database
#    Pastikan MySQL berjalan, lalu buat DB `db_upfotostudio`.
#    Impor dump awal:
#      mysql -u root db_upfotostudio < db_upfotostudio.sql
php artisan migrate          # jalankan migrasi yang belum diterapkan

# 5. Build asset
npm run build                # atau: npm run dev (mode watch)

# 6. Jalankan server
php artisan serve --port=8080
#    → http://127.0.0.1:8080
```

**Via Laragon vhost (opsional):**
- Vhost: `C:\laragon\etc\apache2\sites-enabled\auto.upfotostudio.test.conf` → DocumentRoot ke `public/`
- `APP_URL=http://upfotostudio.test`
- Pastikan `upfotostudio.test` terdaftar di file `hosts` (restart Laragon agar otomatis ditambahkan).

**Akun demo:**

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@upfoto.test` | `admin12345` |
| Owner | `owner@upfoto.test` | `owner12345` |

---

## 4. Konfigurasi Environment (.env)

Variabel penting (lihat `.env.example`):

```dotenv
APP_URL=http://upfotostudio.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_upfotostudio
DB_USERNAME=root
DB_PASSWORD=

# Payment gateway: mock | midtrans
PAYMENT_GATEWAY=mock

# Midtrans (jika PAYMENT_GATEWAY=midtrans)
MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_EXPIRY_MINUTES=30

# Mail (default Mailpit untuk dev)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

---

## 5. Struktur Proyek

```
app/
├── Contracts/Payments/PaymentGatewayInterface.php   # kontrak gateway
├── Http/Controllers/
│   ├── Auth/AuthController.php                       # login/logout admin & owner
│   ├── Frontend/                                     # publik: Home, Booking, Contact, PaymentRedirect
│   ├── Backend/Admin/                                # Dashboard, Studio, ServicePackage, Booking,
│   │                                                 #   Transaction, Content, ContactMessage, Report
│   ├── Backend/Owner/                                # Dashboard, Report
│   └── Api/                                          # MidtransNotificationController, PaymentCallbackController
├── Http/Requests/Frontend/StoreBookingRequest.php
├── Models/                                           # User, Guest, Studio, ServicePackage,
│                                                     #   Booking, PaymentTransaction, WebsiteContent, ContactMessage
├── Mail/                                             # BookingPendingMail, BookingConfirmedMail
└── Services/
    ├── Bookings/BookingService.php                   # orkestrasi pembuatan booking (atomik)
    ├── Payments/PaymentService.php                   # logika transaksi & notifikasi
    ├── Payments/MockQrisGateway.php                  # gateway simulasi lokal
    ├── Payments/MidtransSnapGateway.php              # gateway Midtrans Snap (HTTP client)
    └── Reports/ReportService.php

routes/web.php                                        # seluruh rute web + payment redirect
routes/api.php                                        # webhook notifikasi
resources/views/
├── frontend/   backend/admin   backend/owner
├── layouts/    (frontend.blade.php, dashboard.blade.php)
├── pdf/        (invoices, reports)
└── emails/
docs/                                                 # dokumentasi (termasuk file ini)
```

---

## 6. Arsitektur Aplikasi

Pola yang digunakan: **Controller tipis + Service layer**.

- **Controller** menangani HTTP (validasi request, render view, redirect).
- **Service** memuat logika bisnis (booking, pembayaran, laporan).
- **Payment gateway** dipasang via **dependency injection** menggunakan interface, sehingga implementasi bisa ditukar lewat env tanpa mengubah pemanggil.

```
PaymentGatewayInterface  ← di-bind di AppServiceProvider berdasarkan PAYMENT_GATEWAY
   ├── MockQrisGateway        (PAYMENT_GATEWAY=mock)
   └── MidtransSnapGateway    (PAYMENT_GATEWAY=midtrans)
```

`BookingService` & `PaymentService` menerima `PaymentGatewayInterface` lewat constructor.

---

## 7. Model & Skema Database

Migrasi berada di `database/migrations/` (prefix `2026_03_*`).

| Model | Tabel | Catatan penting |
|-------|-------|-----------------|
| `User` | `users` | kolom `role` ditambahkan via migrasi terpisah (admin/owner) |
| `Guest` | `guests` | tamu pemesan; `firstOrCreate` by email+phone |
| `Studio` | `studios` | punya `is_active` |
| `ServicePackage` | `service_packages` | `price`, `duration_minutes`, `is_active`, `image_path` |
| `Booking` | `bookings` | inti pemesanan |
| `PaymentTransaction` | `payment_transactions` | 1:1 dengan booking |
| `WebsiteContent` | `website_contents` | konten dinamis + `image_path` |
| `ContactMessage` | `contact_messages` | pesan dari form kontak |

### Konstanta `Booking`

```php
STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT'
STATUS_CONFIRMED       = 'CONFIRMED'
STATUS_CANCELLED       = 'CANCELLED'
STATUS_COMPLETED       = 'COMPLETED'

PAYMENT_DP    = 'DP'      // bayar 30% di muka
PAYMENT_LUNAS = 'LUNAS'   // bayar penuh
```

### Relasi

```
Guest 1───* Booking *───1 Studio
                │
                *───1 ServicePackage
                │
Booking 1───1 PaymentTransaction
```

- `Booking::guest()`, `studio()`, `servicePackage()` → `belongsTo`
- `Booking::paymentTransaction()` → `hasOne`

Identifier dibuat di `BookingService`:
- `booking_code` → `BOOK-<YmdHis>-<RAND4>`
- `invoice_number` → `INV-<YmdHis>-<RAND4>` (juga dipakai sebagai `order_id` Midtrans)

---

## 8. Routing

Semua rute web ada di `routes/web.php`.

### Publik (frontend)
| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| GET | `/` | `frontend.home` | `HomeController@home` |
| GET | `/paket-harga` | `frontend.pricing` | `HomeController@pricing` |
| GET | `/booking/paket/{servicePackage}` | `frontend.booking.package-detail` | `BookingController@packageDetail` |
| GET | `/booking/order` | `frontend.booking.order` | `BookingController@order` |
| POST | `/booking` | `frontend.booking.store` | `BookingController@store` |
| GET | `/booking/status/{invoiceNumber}` | `frontend.booking.status` | `BookingController@status` |
| GET | `/booking/invoice/{invoiceNumber}` | `frontend.booking.invoice` | `BookingController@invoice` |
| GET | `/booking/paket/{servicePackage}/slots` | `frontend.booking.slots` | `BookingController@slotsJson` (AJAX) |
| GET | `/booking/studios/{studio}/packages` | `frontend.booking.packages-by-studio` | JSON helper |
| POST | `/kontak` | `frontend.contact.store` | `ContactMessageController@store` |

### Payment redirect (didaftarkan di dashboard Midtrans)
`/payment/finish`, `/payment/unfinish`, `/payment/error` → `PaymentRedirectController`. `finish` melakukan sinkronisasi via Midtrans Status API agar localhost tetap berfungsi tanpa webhook.

### Admin — prefix `/admin`, name `admin.`, middleware `auth:admin` + `role:admin`
- `GET /admin/dashboard`
- `resource studios`, `resource service-packages`, `resource contents` (kecuali `show`)
- `GET /admin/bookings`, `PATCH /admin/bookings/{booking}/status`
- `GET /admin/transactions`
- `GET /admin/contact-messages`, `PATCH .../{contactMessage}/read`
- `GET /admin/reports`, `GET /admin/reports/export/pdf`

### Owner — prefix `/owner`, name `owner.`, middleware `auth:owner` + `role:owner`
- `GET /owner/dashboard`
- `GET /owner/reports`, `GET /owner/reports/export/pdf`, `GET /owner/reports/export/excel`

### API — `routes/api.php`
- `POST /api/payments/midtrans/notification` → `MidtransNotificationController` (verifikasi signature sha512)

---

## 9. Autentikasi & Otorisasi

- Login gabungan untuk admin & owner di `/login` (`AuthController@login`).
- Menggunakan **guard terpisah**: `auth:admin` dan `auth:owner`; group tamu pakai `guest:admin,owner`.
- Otorisasi peran lewat middleware `role:admin` / `role:owner` (berdasar kolom `users.role`).
- Logout terpisah: `POST /admin/logout`, `POST /owner/logout`.
- Frontend publik **tidak** memerlukan login.

---

## 10. Alur Booking

Diorkestrasi oleh `BookingService::createBooking()` — seluruh proses dibungkus `DB::transaction` (atomik).

1. **Lock & validasi** Studio dan ServicePackage (`lockForUpdate`, harus `is_active`).
2. **Hitung waktu**: `end_time = start_time + duration_minutes`.
3. **Validasi jadwal**:
   - Tidak boleh di masa lalu.
   - Harus dalam jam operasional **10:00–21:00** (sesi selesai sebelum tutup).
   - **Anti double-booking**: cek interval overlap pada studio yang sama untuk status `PENDING_PAYMENT`/`CONFIRMED` (`lockForUpdate`).
4. **Guest**: `firstOrCreate` berdasarkan email+phone; update nama bila berubah.
5. **Hitung biaya**: `total_amount = price + add_on_amount`. Untuk DP, `amount = round(total * 0.3)`; LUNAS = total.
6. **Buat `Booking`** (status `PENDING_PAYMENT`) dan **`PaymentTransaction`** (status `PENDING`, method `QRIS`).
7. **Panggil gateway** `createQrisPayment()` → simpan `gateway_reference`, `qr_payload`, `expires_at` (default +30 menit).
8. **Kirim email** instruksi pembayaran via `DB::afterCommit` (kegagalan email di-log, tidak menggagalkan booking).

### Perhitungan slot (`BookingController::buildAvailableSlots`)
- Slot dibuat tiap **30 menit** dari 10:00 sampai sebelum 21:00.
- Sebuah slot `available` jika: sesi selesai ≤ jam tutup, **dan** belum lewat, **dan** tidak overlap dengan booking aktif.
- Endpoint AJAX: `GET /booking/paket/{servicePackage}/slots?date=YYYY-MM-DD`.

### Add-on
Katalog di-hardcode di `BookingController::getAddOnCatalog()` (Extra Print, Jas Hitam, Costume, Extra Time, Keychain). Add-on, pilihan background, dan consent media sosial digabung ke kolom `notes`.

> **Catatan:** menambah add-on baru cukup menambah entri pada `getAddOnCatalog()`. Pilihan dari user divalidasi ulang terhadap katalog di `resolveSelectedAddOns()` (qty ≥ 1).

---

## 11. Payment Gateway

Pluggable melalui env `PAYMENT_GATEWAY`:

| Nilai | Implementasi | Untuk |
|-------|--------------|-------|
| `mock` | `MockQrisGateway` | Pengembangan lokal tanpa kredensial |
| `midtrans` | `MidtransSnapGateway` | Sandbox / production Midtrans |

- Binding interface `PaymentGatewayInterface` dilakukan di `AppServiceProvider`.
- Midtrans **Snap** diintegrasikan memakai **Laravel HTTP client** (tanpa SDK resmi).
- `invoice_number` = Midtrans `order_id`.
- **Webhook**: `POST /api/payments/midtrans/notification` (signature sha512 diverifikasi) → `PaymentService::processMidtransNotification`.
- **Redirect**: `/payment/{finish,unfinish,error}`. `finish` menyinkronkan status via Midtrans Status API sehingga localhost tetap berfungsi tanpa webhook publik.
- Dokumentasi setup detail: `docs/MIDTRANS_SETUP.md`.

### Kontrak gateway
`PaymentGatewayInterface::createQrisPayment(array $payload)` menerima `invoice_number`, `amount`, `customer_*`, `booking_code` dan mengembalikan array berisi `reference`, `qr_string`, `payment_url`, `expires_at`.

---

## 12. Laporan & Ekspor

- `ReportService` menyiapkan agregasi data laporan.
- **Admin**: ekspor **PDF** (DomPDF, view `resources/views/pdf/reports/`).
- **Owner**: ekspor **PDF** dan **Excel** (Maatwebsite/Excel).
- Invoice PDF tamu: `BookingController::invoice()` → view `pdf/invoices/invoice`, ukuran A4 portrait.

---

## 13. Email

- `BookingPendingMail` — instruksi pembayaran, dikirim setelah booking dibuat (via `afterCommit`).
- `BookingConfirmedMail` — konfirmasi setelah pembayaran sukses.
- View: `resources/views/emails/booking-pending.blade.php`, `booking-confirmed.blade.php`.
- Dev: gunakan **Mailpit** (`MAIL_HOST=mailpit`, port 1025) untuk menangkap email.

---

## 14. Perintah Penting

```powershell
# Server dev
php artisan serve --port=8080

# Migrasi
php artisan migrate
php artisan migrate:status
php artisan migrate:fresh --seed     # reset (HATI-HATI: menghapus data)

# Cache & config
php artisan optimize:clear
php artisan config:clear
php artisan route:list

# Asset
npm run dev      # watch
npm run build    # produksi

# Tinker
php artisan tinker
```

---

## 15. Troubleshooting (Laragon/Windows)

| Masalah | Solusi |
|---------|--------|
| `composer install` gagal karena versi PHP | Tambah flag `--ignore-platform-reqs` (lock dibuat di PHP 8.4, runtime 8.2). |
| Laravel error "could not connect to database" | Pastikan MySQL berjalan: `Start-Process mysqld`. Cek `DB_DATABASE=db_upfotostudio`. |
| `upfotostudio.test` tidak bisa dibuka | Restart Laragon atau tambahkan host manual di `C:\Windows\System32\drivers\etc\hosts`. |
| Halaman blank / asset hilang | Jalankan `npm run build` lalu `php artisan optimize:clear`. |
| Perubahan rute/config tidak terbaca | `php artisan config:clear && php artisan route:clear`. |
| Webhook Midtrans tak masuk di localhost | Andalkan redirect `/payment/finish` (sinkron via Status API), atau gunakan tunnel publik (mis. ngrok). |
| Email tidak terkirim | Cek konfigurasi `MAIL_*`; untuk dev jalankan Mailpit. Kegagalan email di-log, tidak menggagalkan booking. |

### Lokasi penting (lingkungan lokal user)
- Proyek: `c:\laragon\www\UPFotoStudio\UPFotoStudio\`
- MySQL binary: `C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\`

---

*Untuk panduan penggunaan non-teknis, lihat `docs/MANUAL_PENGGUNA.md`.*
