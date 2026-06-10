<?php

namespace Database\Seeders;

use App\Models\WebsiteContent;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    /**
     * Seed konten dinamis website publik.
     */
    public function run(): void
    {
        $contents = [
            [
                'key' => 'home_hero',
                'title' => 'Sistem Booking Ruang Photostudio',
                'content' => 'Booking studio online dengan pembayaran digital realtime QRIS, cepat dan aman.',
            ],
            [
                'key' => 'home_gallery_section',
                'title' => 'Preview Galeri',
                'content' => 'Lihat Semua',
            ],
            [
                'key' => 'home_service_section',
                'title' => 'Layanan UPFotoStudio',
                'content' => 'Lihat Paket Harga',
            ],
            [
                'key' => 'home_faq_section',
                'title' => 'FAQ',
                'content' => 'Pertanyaan yang paling sering ditanyakan sebelum booking studio.',
            ],
            [
                'key' => 'home_why_choose',
                'title' => 'Kenapa pilih kami?',
                'content' => 'Keunggulan utama untuk pengalaman studio yang nyaman.',
            ],
            [
                'key' => 'home_why_choose_item_1',
                'title' => 'Studio bersih dan nyaman untuk semua jenis sesi foto.',
                'content' => null,
            ],
            [
                'key' => 'home_why_choose_item_2',
                'title' => 'Pembayaran digital realtime via QRIS.',
                'content' => null,
            ],
            [
                'key' => 'home_why_choose_item_3',
                'title' => 'Sistem booking otomatis, anti bentrok jadwal.',
                'content' => null,
            ],
            [
                'key' => 'home_why_choose_item_4',
                'title' => 'Invoice PDF dan status booking bisa dipantau online.',
                'content' => null,
            ],
            [
                'key' => 'home_promo_slide_1',
                'title' => 'First Flipbook Photobooth',
                'content' => 'Promo design terbaru untuk pengalaman photobooth yang lebih seru.',
                'image_path' => 'assets/images/home/promo/promo-1.svg',
            ],
            [
                'key' => 'home_promo_slide_2',
                'title' => 'Level Up Your Photos',
                'content' => 'Abadikan momen bersama teman dan keluarga dengan kualitas studio profesional.',
                'image_path' => 'assets/images/home/promo/promo-2.svg',
            ],
            [
                'key' => 'home_promo_slide_3',
                'title' => 'Explore Moments',
                'content' => 'Pilih tipe sesi favoritmu: couple, group, solo, hingga kebutuhan ID photo.',
                'image_path' => 'assets/images/home/promo/promo-3.svg',
            ],
            [
                'key' => 'home_faq_1',
                'title' => 'Bagaimana cara booking studio?',
                'content' => 'Pilih studio, tanggal, jam mulai, lalu pilih paket layanan. Sistem otomatis menghitung jam selesai dan total pembayaran.',
            ],
            [
                'key' => 'home_faq_2',
                'title' => 'Apakah bisa pembayaran DP?',
                'content' => 'Bisa. DP dihitung 30% dari total biaya, dengan nilai minimum Rp50.000 sesuai aturan sistem.',
            ],
            [
                'key' => 'home_faq_3',
                'title' => 'Bagaimana jika jadwal bentrok?',
                'content' => 'Sistem menolak otomatis booking yang overlap pada studio yang sama jika status booking lain masih PENDING_PAYMENT atau CONFIRMED.',
            ],
            [
                'key' => 'home_faq_4',
                'title' => 'Setelah bayar, kapan booking dikonfirmasi?',
                'content' => 'Booking akan berubah menjadi CONFIRMED secara realtime saat callback pembayaran mengirim status SUCCESS.',
            ],
            [
                'key' => 'about_page',
                'title' => 'Tentang Kami',
                'content' => 'UPFotoStudio hadir untuk membantu kebutuhan foto personal, keluarga, hingga branding bisnis.',
            ],
            [
                'key' => 'gallery_page',
                'title' => 'Galeri',
                'content' => 'Contoh hasil foto dari berbagai studio dan paket layanan kami.',
            ],
            [
                'key' => 'gallery_item_1',
                'title' => 'Preview Foto 1',
                'content' => 'Dokumentasi sesi foto keluarga.',
                'image_path' => 'assets/images/home/gallery/gallery-1.svg',
            ],
            [
                'key' => 'gallery_item_2',
                'title' => 'Preview Foto 2',
                'content' => 'Sesi portrait profesional untuk kebutuhan branding.',
                'image_path' => 'assets/images/home/gallery/gallery-2.svg',
            ],
            [
                'key' => 'gallery_item_3',
                'title' => 'Preview Foto 3',
                'content' => 'Konsep studio minimalis dengan pencahayaan soft.',
                'image_path' => 'assets/images/home/gallery/gallery-3.svg',
            ],
            [
                'key' => 'gallery_item_4',
                'title' => 'Preview Foto 4',
                'content' => 'Sesi couple dengan tema color mood modern.',
                'image_path' => 'assets/images/home/gallery/gallery-4.svg',
            ],
            [
                'key' => 'gallery_item_5',
                'title' => 'Preview Foto 5',
                'content' => 'Paket group session untuk momen komunitas.',
                'image_path' => 'assets/images/home/gallery/gallery-5.svg',
            ],
            [
                'key' => 'gallery_item_6',
                'title' => 'Preview Foto 6',
                'content' => 'Koleksi highlight terbaru dari studio kami.',
                'image_path' => 'assets/images/home/gallery/gallery-1.svg',
            ],
            [
                'key' => 'pricing_page',
                'title' => 'Paket Harga',
                'content' => 'Pilih paket sesuai durasi yang Anda butuhkan. Harga transparan dan fleksibel.',
            ],
            [
                'key' => 'pricing_package_image_1',
                'title' => 'Gambar Paket 1',
                'content' => 'assets/images/home/gallery/gallery-1.svg',
            ],
            [
                'key' => 'pricing_package_image_2',
                'title' => 'Gambar Paket 2',
                'content' => 'assets/images/home/gallery/gallery-2.svg',
            ],
            [
                'key' => 'pricing_package_image_3',
                'title' => 'Gambar Paket 3',
                'content' => 'assets/images/home/gallery/gallery-3.svg',
            ],
            [
                'key' => 'pricing_package_image_4',
                'title' => 'Gambar Paket 4',
                'content' => 'assets/images/home/gallery/gallery-4.svg',
            ],
            [
                'key' => 'pricing_package_image_5',
                'title' => 'Gambar Paket 5',
                'content' => 'assets/images/home/gallery/gallery-5.svg',
            ],
            [
                'key' => 'terms_page',
                'title' => 'Syarat dan Ketentuan',
                'content' => 'Booking aktif setelah pembayaran berhasil. DP minimal 30% atau minimum Rp50.000.',
            ],
            [
                'key' => 'terms_item_1',
                'title' => 'Booking overlap pada studio dan waktu yang sama akan ditolak otomatis.',
                'content' => null,
            ],
            [
                'key' => 'terms_item_2',
                'title' => 'Status booking aktif jika transaksi QRIS berstatus SUCCESS.',
                'content' => null,
            ],
            [
                'key' => 'terms_item_3',
                'title' => 'Pembayaran DP dihitung 30% dari total atau minimal Rp50.000.',
                'content' => null,
            ],
            [
                'key' => 'contact_page',
                'title' => 'Kontak',
                'content' => 'WhatsApp: 0812-0000-0000 | Email: hello@upfotostudio.test',
            ],
            [
                'key' => 'footer_brand',
                'title' => 'UPFotoStudio',
                'content' => null,
            ],
            [
                'key' => 'footer_description',
                'title' => 'Footer Description',
                'content' => 'Studio foto profesional untuk kebutuhan personal, keluarga, dan bisnis.',
            ],
            [
                'key' => 'footer_contact',
                'title' => 'Footer Contact',
                'content' => 'WhatsApp: 0812-0000-0000 | Email: hello@upfotostudio.test',
            ],
            [
                'key' => 'footer_copyright',
                'title' => 'Footer Copyright',
                'content' => 'Copyright 2026 UPFotoStudio.',
            ],
        ];

        foreach ($contents as $content) {
            $content['image_path'] = $content['image_path'] ?? null;
            WebsiteContent::updateOrCreate(['key' => $content['key']], $content);
        }
    }
}
