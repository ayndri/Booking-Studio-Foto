<?php

namespace Database\Seeders;

use App\Models\WebsiteContent;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    /**
     * Seed konten dinamis website publik sesuai data produksi terkini.
     */
    public function run(): void
    {
        $contents = [
            ['key' => 'home_hero', 'title' => 'Sistem Booking Ruang Photostudio', 'content' => 'Booking studio online dengan pembayaran digital realtime QRIS, cepat dan aman.', 'image_path' => null],
            ['key' => 'about_page', 'title' => 'Tentang Kami', 'content' => 'UPFotoStudio hadir untuk membantu kebutuhan foto personal, keluarga, hingga branding bisnis.', 'image_path' => null],
            ['key' => 'gallery_page', 'title' => 'Galeri', 'content' => 'Contoh hasil foto dari berbagai studio dan paket layanan kami.', 'image_path' => null],
            ['key' => 'pricing_page', 'title' => 'Paket Harga', 'content' => 'Pilih paket sesuai durasi yang Anda butuhkan. Harga transparan dan fleksibel.', 'image_path' => null],
            ['key' => 'terms_page', 'title' => 'Syarat dan Ketentuan', 'content' => 'Booking aktif setelah pembayaran berhasil. DP minimal 30% atau minimum Rp50.000.', 'image_path' => null],
            ['key' => 'contact_page', 'title' => 'Kontak', 'content' => 'WhatsApp: 0812-0000-0000 | Email: hello@upfotostudio.test', 'image_path' => null],
            ['key' => 'home_gallery_section', 'title' => 'Preview Galeri', 'content' => 'Lihat Semua', 'image_path' => null],
            ['key' => 'home_service_section', 'title' => 'Layanan UPFotoStudio', 'content' => 'Lihat Paket Harga', 'image_path' => null],
            ['key' => 'home_faq_section', 'title' => 'FAQ', 'content' => 'Pertanyaan yang paling sering ditanyakan sebelum booking studio.', 'image_path' => null],
            ['key' => 'home_why_choose', 'title' => 'Kenapa pilih kami?', 'content' => 'Keunggulan utama untuk pengalaman studio yang nyaman.', 'image_path' => null],
            ['key' => 'home_why_choose_item_1', 'title' => 'Studio bersih dan nyaman untuk semua jenis sesi foto.', 'content' => null, 'image_path' => null],
            ['key' => 'home_why_choose_item_2', 'title' => 'Pembayaran digital realtime via QRIS.', 'content' => null, 'image_path' => null],
            ['key' => 'home_why_choose_item_3', 'title' => 'Sistem booking otomatis, anti bentrok jadwal.', 'content' => null, 'image_path' => null],
            ['key' => 'home_why_choose_item_4', 'title' => 'Invoice PDF dan status booking bisa dipantau online.', 'content' => null, 'image_path' => null],
            ['key' => 'home_promo_slide_1', 'title' => 'First Flipbook Photobooth', 'content' => '{"caption":"Promo design terbaru untuk pengalaman photobooth yang lebih seru.","image":"assets/images/home/promo/promo-1.svg"}', 'image_path' => null],
            ['key' => 'home_promo_slide_2', 'title' => 'Level Up Your Photos', 'content' => '{"caption":"Abadikan momen bersama teman dan keluarga dengan kualitas studio profesional.","image":"assets/images/home/promo/promo-2.svg"}', 'image_path' => null],
            ['key' => 'home_promo_slide_3', 'title' => 'Explore Moments', 'content' => '{"caption":"Pilih tipe sesi favoritmu: couple, group, solo, hingga kebutuhan ID photo.","image":"assets/images/home/promo/promo-3.svg"}', 'image_path' => null],
            ['key' => 'home_faq_1', 'title' => 'Bagaimana cara booking studio?', 'content' => 'Pilih studio, tanggal, jam mulai, lalu pilih paket layanan. Sistem otomatis menghitung jam selesai dan total pembayaran.', 'image_path' => null],
            ['key' => 'home_faq_2', 'title' => 'Apakah bisa pembayaran DP?', 'content' => 'Bisa. DP dihitung 30% dari total biaya, dengan nilai minimum Rp50.000 sesuai aturan sistem.', 'image_path' => null],
            ['key' => 'home_faq_3', 'title' => 'Bagaimana jika jadwal bentrok?', 'content' => 'Sistem menolak otomatis booking yang overlap pada studio yang sama jika status booking lain masih PENDING_PAYMENT atau CONFIRMED.', 'image_path' => null],
            ['key' => 'home_faq_4', 'title' => 'Setelah bayar, kapan booking dikonfirmasi?', 'content' => 'Booking akan berubah menjadi CONFIRMED secara realtime saat callback pembayaran mengirim status SUCCESS.', 'image_path' => null],
            ['key' => 'gallery_item_1', 'title' => 'Preview Foto 1', 'content' => '{"image":"assets/images/home/gallery/gallery-1.svg","caption":"Dokumentasi sesi foto keluarga."}', 'image_path' => null],
            ['key' => 'gallery_item_2', 'title' => 'Preview Foto 2', 'content' => '{"image":"assets/images/home/gallery/gallery-2.svg","caption":"Sesi portrait profesional untuk kebutuhan branding."}', 'image_path' => null],
            ['key' => 'gallery_item_3', 'title' => 'Preview Foto 3', 'content' => '{"image":"assets/images/home/gallery/gallery-3.svg","caption":"Konsep studio minimalis dengan pencahayaan soft."}', 'image_path' => null],
            ['key' => 'gallery_item_4', 'title' => 'Preview Foto 4', 'content' => '{"image":"assets/images/home/gallery/gallery-4.svg","caption":"Sesi couple dengan tema color mood modern."}', 'image_path' => null],
            ['key' => 'gallery_item_5', 'title' => 'Preview Foto 5', 'content' => '{"image":"assets/images/home/gallery/gallery-5.svg","caption":"Paket group session untuk momen komunitas."}', 'image_path' => null],
            ['key' => 'gallery_item_6', 'title' => 'Preview Foto 6', 'content' => '{"image":"assets/images/home/gallery/gallery-1.svg","caption":"Koleksi highlight terbaru dari studio kami."}', 'image_path' => null],
            ['key' => 'pricing_package_image_1', 'title' => 'Gambar Paket 1', 'content' => 'assets/images/home/gallery/gallery-1.svg', 'image_path' => null],
            ['key' => 'pricing_package_image_2', 'title' => 'Gambar Paket 2', 'content' => 'assets/images/home/gallery/gallery-2.svg', 'image_path' => null],
            ['key' => 'pricing_package_image_3', 'title' => 'Gambar Paket 3', 'content' => 'assets/images/home/gallery/gallery-3.svg', 'image_path' => null],
            ['key' => 'pricing_package_image_4', 'title' => 'Gambar Paket 4', 'content' => 'assets/images/home/gallery/gallery-4.svg', 'image_path' => null],
            ['key' => 'pricing_package_image_5', 'title' => 'Gambar Paket 5', 'content' => 'assets/images/home/gallery/gallery-5.svg', 'image_path' => null],
            ['key' => 'terms_item_1', 'title' => 'Booking overlap pada studio dan waktu yang sama akan ditolak otomatis.', 'content' => null, 'image_path' => null],
            ['key' => 'terms_item_2', 'title' => 'Status booking aktif jika transaksi QRIS berstatus SUCCESS.', 'content' => null, 'image_path' => null],
            ['key' => 'terms_item_3', 'title' => 'Pembayaran DP dihitung 30% dari total atau minimal Rp50.000.', 'content' => null, 'image_path' => null],
            ['key' => 'about_value_1', 'title' => 'Kolaboratif', 'content' => 'Kami terbiasa bekerja bareng make-up artist, stylist, dan tim konten klien.', 'image_path' => null],
            ['key' => 'about_value_2', 'title' => 'Transparan', 'content' => 'Informasi harga, durasi, dan output dijelaskan dari awal supaya ekspektasi lebih jelas.', 'image_path' => null],
            ['key' => 'about_value_3', 'title' => 'Ramah & Nyaman', 'content' => 'Studio bersih, staf ramah, dan suasana sesi foto dibuat senyaman mungkin.', 'image_path' => null],
            ['key' => 'about_value_4', 'title' => 'Cepat Beradaptasi', 'content' => 'Kami fleksibel menyesuaikan konsep visual sesuai kebutuhan personal maupun bisnis.', 'image_path' => null],
            ['key' => 'about_story_1', 'title' => 'Awal Mula', 'content' => 'UPFotoStudio dimulai dari satu ruang kecil dengan tujuan sederhana: bikin sesi foto terasa nyaman untuk siapa saja.', 'image_path' => null],
            ['key' => 'about_story_2', 'title' => 'Berkembang', 'content' => 'Seiring waktu, kami berkembang dengan menambah set studio, alur booking online, dan tim kreatif yang lebih solid.', 'image_path' => null],
            ['key' => 'about_story_3', 'title' => 'Klien Setia', 'content' => 'Banyak klien datang untuk kebutuhan personal, lalu kembali lagi untuk branding bisnis dan konten campaign.', 'image_path' => null],
            ['key' => 'about_story_4', 'title' => 'Terus Berkembang', 'content' => 'Saat ini kami masih terus bereksperimen dan memperbaiki detail layanan agar standar studio semakin matang.', 'image_path' => null],
            ['key' => 'terms_flow_1', 'title' => 'Pilih Paket & Slot', 'content' => 'Customer memilih paket, studio, tanggal, dan jam yang masih tersedia.', 'image_path' => null],
            ['key' => 'terms_flow_2', 'title' => 'Isi Data Pemesan', 'content' => 'Data kontak aktif dibutuhkan agar status booking dan invoice mudah dipantau.', 'image_path' => null],
            ['key' => 'terms_flow_3', 'title' => 'Konfirmasi Pembayaran', 'content' => 'Sistem membuat invoice dan menunggu status pembayaran QRIS berhasil.', 'image_path' => null],
            ['key' => 'terms_flow_4', 'title' => 'Booking Dikonfirmasi', 'content' => 'Setelah transaksi sukses, status booking berubah menjadi CONFIRMED secara otomatis.', 'image_path' => null],
            ['key' => 'terms_extra_1', 'title' => 'Komplain Kualitas', 'content' => 'Segala bentuk komplain kualitas wajib diajukan maksimal 2x24 jam setelah file diterima.', 'image_path' => null],
            ['key' => 'terms_extra_2', 'title' => 'Perubahan Jadwal', 'content' => 'Perubahan jadwal di hari yang sama mengikuti ketersediaan slot dan dapat dikenakan biaya operasional.', 'image_path' => null],
            ['key' => 'terms_extra_3', 'title' => 'Konsep Khusus', 'content' => 'Permintaan konsep khusus di luar paket standar dapat memerlukan konfirmasi tambahan dari tim.', 'image_path' => null],
            ['key' => 'terms_extra_4', 'title' => 'Pengiriman File', 'content' => 'File hasil foto dikirim sesuai estimasi proses editing yang diinformasikan setelah sesi selesai.', 'image_path' => null],
            ['key' => 'terms_extra_5', 'title' => 'Behind The Scenes', 'content' => 'Dokumentasi behind-the-scenes hanya dilakukan jika pelanggan memberikan persetujuan sebelumnya.', 'image_path' => null],
        ];

        foreach ($contents as $content) {
            WebsiteContent::updateOrCreate(['key' => $content['key']], $content);
        }
    }
}
