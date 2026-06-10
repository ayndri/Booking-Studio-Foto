# Manual Book Pengguna — UPFotoStudio

Panduan lengkap penggunaan website **UPFotoStudio** untuk pelanggan (pengunjung), Admin, dan Owner.

> **Apa itu UPFotoStudio?**
> Website untuk memesan (booking) sewa ruang studio foto secara online, lengkap dengan pemilihan paket, jadwal, add-on, dan pembayaran QRIS.

---

## Daftar Isi

1. [Jenis Pengguna](#1-jenis-pengguna)
2. [Panduan Pelanggan (Tanpa Login)](#2-panduan-pelanggan-tanpa-login)
   - [Menjelajah Website](#21-menjelajah-website)
   - [Cara Melakukan Booking](#22-cara-melakukan-booking)
   - [Pembayaran QRIS](#23-pembayaran-qris)
   - [Cek Status & Unduh Invoice](#24-cek-status--unduh-invoice)
   - [Menghubungi Studio](#25-menghubungi-studio)
3. [Panduan Admin](#3-panduan-admin)
4. [Panduan Owner](#4-panduan-owner)
5. [Pertanyaan Umum (FAQ)](#5-pertanyaan-umum-faq)

---

## 1. Jenis Pengguna

| Pengguna | Akses | Login? |
|----------|-------|--------|
| **Pelanggan / Tamu** | Melihat website, melakukan booking, membayar | Tidak perlu |
| **Admin** | Mengelola studio, paket, booking, transaksi, konten, pesan, laporan | Ya |
| **Owner** | Melihat dashboard & laporan bisnis, ekspor PDF/Excel | Ya |

Akun login demo:

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@upfoto.test` | `admin12345` |
| Owner | `owner@upfoto.test` | `owner12345` |

Halaman login: **`/login`**

---

## 2. Panduan Pelanggan (Tanpa Login)

### 2.1 Menjelajah Website

Halaman publik yang tersedia:

| Menu | Alamat | Isi |
|------|--------|-----|
| Beranda | `/` | Halaman utama & highlight studio |
| Tentang Kami | `/tentang-kami` | Profil studio |
| Galeri | `/galeri` | Contoh hasil foto |
| Paket & Harga | `/paket-harga` | Daftar paket yang bisa dipesan |
| Syarat & Ketentuan | `/syarat-ketentuan` | Aturan layanan |
| Kontak | `/kontak` | Form & info kontak |

### 2.2 Cara Melakukan Booking

Alur pemesanan terdiri dari 4 langkah:

```
Pilih Paket  →  Pilih Tanggal & Jam  →  Isi Data + Add-on  →  Bayar QRIS
```

**Langkah 1 — Pilih Paket**
1. Buka menu **Paket & Harga** (`/paket-harga`).
2. Pilih paket yang diinginkan, lalu klik untuk masuk ke halaman detail.

**Langkah 2 — Pilih Tanggal & Jam**
1. Di halaman detail paket, gunakan **kalender** untuk memilih tanggal.
   - Tanggal yang sudah lewat tidak bisa dipilih.
2. Pilih **slot jam** yang tersedia.
   - Jam operasional studio: **10:00 – 21:00**.
   - Slot yang sudah dipesan orang lain atau sudah lewat akan tampil **tidak tersedia**.
   - Durasi sesi mengikuti durasi paket (sistem otomatis menghitung jam selesai).

**Langkah 3 — Isi Data & Tambah Add-on**
1. Lengkapi **data pemesan**: nama, email, dan nomor telepon.
2. (Opsional) Pilih **add-on** tambahan:

   | Add-on | Harga | Satuan |
   |--------|-------|--------|
   | Extra Print (foto 4R) | Rp 20.000 | /pcs |
   | Jas Hitam | Rp 15.000 | /costume |
   | Costume | Rp 15.000 | /costume |
   | Extra Time (+7 menit) | Rp 20.000 | /7 menit |
   | Keychain | Rp 15.000 | /2 pcs |

3. (Opsional) Pilih **background** dan izin **upload ke media sosial**.
4. Pilih **tipe pembayaran**:
   - **DP** — bayar uang muka **30%** dari total.
   - **LUNAS** — bayar penuh.
5. Klik **Pesan / Lanjut Bayar**.

> Total = harga paket + total add-on. Jika memilih **DP**, nominal yang dibayar = 30% dari total.

### 2.3 Pembayaran QRIS

1. Setelah booking dibuat, Anda diarahkan ke halaman pembayaran **QRIS**.
2. Scan QR Code menggunakan aplikasi e-wallet / mobile banking yang mendukung QRIS.
3. Pembayaran memiliki **batas waktu (± 30 menit)**. Jika lewat, booking otomatis kedaluwarsa dan slot dilepas kembali.
4. Email instruksi pembayaran juga dikirim ke email pemesan.

> Status booking saat menunggu pembayaran: **PENDING_PAYMENT**. Setelah dibayar dan terverifikasi, status menjadi **CONFIRMED**.

### 2.4 Cek Status & Unduh Invoice

- **Cek status pembayaran:** buka `/booking/status/{NOMOR-INVOICE}`
  (contoh: `/booking/status/INV-20260610xxxxxx-AB12`).
- **Unduh invoice PDF:** buka `/booking/invoice/{NOMOR-INVOICE}` — file PDF akan otomatis terunduh.

Nomor invoice (diawali `INV-`) dapat ditemukan di halaman setelah booking dan di email yang dikirim.

### 2.5 Menghubungi Studio

1. Buka menu **Kontak** (`/kontak`).
2. Isi nama, email, dan pesan, lalu kirim.
3. Pesan Anda akan masuk ke dashboard Admin untuk ditindaklanjuti.

---

## 3. Panduan Admin

Login di `/login` dengan akun admin, lalu masuk ke **Dashboard Admin** (`/admin/dashboard`).

### Menu Admin

| Menu | Fungsi |
|------|--------|
| **Dashboard** | Ringkasan statistik (booking, pendapatan, dll.) |
| **Studios** | Tambah / ubah / hapus / aktif-nonaktifkan studio |
| **Service Packages** | Kelola paket layanan (nama, harga, durasi, gambar, status aktif) |
| **Bookings** | Lihat semua booking & **ubah status** (PENDING/CONFIRMED/CANCELLED/COMPLETED) |
| **Transactions** | Lihat seluruh transaksi pembayaran |
| **Contents** | Kelola konten website (galeri/banner/teks) |
| **Contact Messages** | Lihat pesan masuk & **tandai sudah dibaca** |
| **Reports** | Lihat laporan & **ekspor PDF** |

### Tugas Umum Admin

**Menambah Studio**
1. Menu **Studios** → tombol **Tambah/Create**.
2. Isi data studio, simpan. Pastikan status **aktif** agar muncul di website.

**Menambah / Mengubah Paket**
1. Menu **Service Packages** → **Tambah** atau **Edit**.
2. Tentukan: studio, nama paket, harga, **durasi (menit)**, gambar, dan status aktif.
   - Durasi memengaruhi perhitungan slot jam dan bonus benefit.

**Mengubah Status Booking**
1. Menu **Bookings**.
2. Pada booking terkait, ubah statusnya (mis. dari PENDING_PAYMENT → CONFIRMED, atau → CANCELLED/COMPLETED).

**Menangani Pesan Masuk**
1. Menu **Contact Messages**.
2. Baca pesan, lalu klik **Tandai Sudah Dibaca**.

**Ekspor Laporan**
1. Menu **Reports** → **Export PDF**.

---

## 4. Panduan Owner

Login di `/login` dengan akun owner, lalu masuk ke **Dashboard Owner** (`/owner/dashboard`).

| Menu | Fungsi |
|------|--------|
| **Dashboard** | Ringkasan performa bisnis |
| **Reports** | Laporan transaksi/pendapatan |
| **Export PDF** | Unduh laporan format PDF |
| **Export Excel** | Unduh laporan format Excel (.xlsx) |

Owner difokuskan untuk **pemantauan & laporan**, bukan operasional harian.

---

## 5. Pertanyaan Umum (FAQ)

**Q: Apakah perlu membuat akun untuk booking?**
A: Tidak. Booking dapat dilakukan langsung sebagai tamu dengan mengisi nama, email, dan telepon.

**Q: Kenapa jam yang saya inginkan tidak bisa dipilih?**
A: Kemungkinan jam tersebut sudah dipesan, sudah lewat, atau sesinya melewati jam tutup (21:00).

**Q: Apa beda DP dan LUNAS?**
A: DP membayar 30% di muka; LUNAS membayar penuh. Sisa DP diselesaikan sesuai ketentuan studio.

**Q: Berapa lama batas waktu pembayaran?**
A: Sekitar 30 menit. Lewat dari itu booking kedaluwarsa dan slot kembali tersedia.

**Q: Di mana saya bisa mengunduh invoice?**
A: Di `/booking/invoice/{NOMOR-INVOICE}` atau melalui tautan pada email/halaman status.

**Q: Saya sudah bayar tapi status masih PENDING.**
A: Tunggu beberapa saat untuk verifikasi otomatis, lalu refresh halaman status. Bila tetap, hubungi studio via menu Kontak.

---

*Dokumen ini ditujukan untuk pengguna akhir. Untuk dokumentasi teknis, lihat `docs/MANUAL_DEVELOPER.md`.*
