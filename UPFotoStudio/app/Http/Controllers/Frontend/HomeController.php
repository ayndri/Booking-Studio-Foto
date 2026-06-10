<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use App\Models\Studio;
use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const HOME_GALLERY_PREVIEW_LIMIT = 5;

    /**
     * Halaman beranda publik.
     */
    public function home(): View
    {
        $hero = $this->getContent(
            'home_hero',
            'Ayoo Booking Studio Impian Anda Disini..',
            'Kami berikan studio dan layanan terbaik sesuai gambar di atas.'
        );
        if ($hero['title'] === 'Sistem Booking Ruang Photostudio') {
            $hero['title'] = 'Ayoo Booking Studio Impian Anda Disini..';
        }
        if ($hero['content'] === 'Booking studio online dengan pembayaran QRIS realtime, aman dan cepat.') {
            $hero['content'] = 'Kami berikan studio dan layanan terbaik sesuai gambar di atas.';
        }

        $gallerySection = $this->getContent('home_gallery_section', 'Galeri Studio', 'Lihat Selengkapnya');
        $serviceSection = $this->getContent('home_service_section', 'Layanan UPFotoStudio', 'Lihat Paket Harga');
        $faqSection = $this->getContent('home_faq_section', 'Pertanyaan Umum', '');
        $whyChooseSection = $this->getContent('home_why_choose', 'Kenapa Pilih Kami?', 'Keunggulan utama untuk pengalaman studio yang nyaman.');

        return view('frontend.home', [
            'hero' => $hero,
            'promoSlides' => $this->buildPromoSlides(),
            'galleryPreview' => $this->buildGalleryPreview(self::HOME_GALLERY_PREVIEW_LIMIT),
            'services' => ServicePackage::query()
                ->with('studio')
                ->where('is_active', true)
                ->orderBy('price')
                ->limit(6)
                ->get(),
            'faqItems' => $this->buildFaqItems(),
            'studios' => Studio::query()->where('is_active', true)->orderBy('name')->get(),
            'gallerySection' => $gallerySection,
            'serviceSection' => $serviceSection,
            'faqSection' => $faqSection,
            'whyChooseSection' => $whyChooseSection,
            'whyChooseItems' => $this->getSimpleListByPrefix('home_why_choose_item_', [
                'Studio bersih dan nyaman untuk semua jenis sesi foto.',
                'Pembayaran digital realtime via QRIS.',
                'Sistem booking otomatis, anti bentrok jadwal.',
                'Invoice PDF dan status booking bisa dipantau online.',
            ]),
        ]);
    }

    /**
     * Halaman tentang kami.
     */
    public function about(): View
    {
        return view('frontend.about', [
            'about' => $this->getContent(
                'about_page',
                'Tentang Kami',
                'UPFotoStudio menyediakan ruang studio berkualitas untuk kebutuhan foto personal maupun komersial.'
            ),
            'aboutStories' => $this->buildRandomAboutStories(),
            'aboutStats' => $this->buildRandomAboutStats(),
            'aboutValues' => $this->buildRandomAboutValues(),
            'aboutPrograms' => $this->buildRandomAboutPrograms(),
        ]);
    }

    /**
     * Halaman galeri.
     */
    public function gallery(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $galleryItems = $this->buildGalleryItems();

        if ($search !== '') {
            $galleryItems = collect($galleryItems)
                ->filter(function (array $item) use ($search): bool {
                    $title = (string) ($item['title'] ?? '');
                    $caption = (string) ($item['caption'] ?? '');

                    return Str::contains($title, $search, true)
                        || Str::contains($caption, $search, true);
                })
                ->values()
                ->all();
        }

        return view('frontend.gallery', [
            'gallery' => $this->getContent(
                'gallery_page',
                'Galeri',
                'Lihat contoh hasil pemotretan dari berbagai paket layanan kami.'
            ),
            'galleryItems' => $galleryItems,
            'search' => $search,
        ]);
    }

    /**
     * Halaman paket harga.
     */
    public function pricing(Request $request): View
    {
        $selectedStudioId = $request->integer('studio_id');

        $studios = Studio::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $packages = ServicePackage::query()
            ->with('studio')
            ->where('is_active', true)
            ->when($selectedStudioId, fn ($query) => $query->where('studio_id', $selectedStudioId))
            ->orderBy('price')
            ->get();

        return view('frontend.pricing', [
            'pricing' => $this->getContent(
                'pricing_page',
                'Paket Harga',
                'Pilih paket sesuai kebutuhan. Durasi dihitung otomatis saat booking.'
            ),
            'studios' => $studios,
            'packages' => $packages,
            'selectedStudioId' => $selectedStudioId,
        ]);
    }

    /**
     * Halaman syarat dan ketentuan.
     */
    public function terms(): View
    {
        return view('frontend.terms', [
            'terms' => $this->getContent(
                'terms_page',
                'Syarat dan Ketentuan',
                'Pembayaran DP minimal 30% dari total biaya dan minimal Rp50.000.'
            ),
            'termItems' => $this->getSimpleListByPrefix('terms_item_', [
                'Booking overlap pada studio dan waktu yang sama akan ditolak otomatis.',
                'Status booking aktif jika transaksi QRIS berstatus SUCCESS.',
                'Pembayaran DP dihitung 30% dari total atau minimal Rp50.000.',
            ]),
            'termExtraItems' => $this->buildRandomTermExtraItems(),
            'termFlow' => $this->buildRandomTermFlow(),
            'termFaqs' => $this->buildRandomTermFaqs(),
        ]);
    }

    /**
     * Halaman kontak.
     */
    public function contact(): View
    {
        return view('frontend.contact', [
            'contact' => $this->getContent(
                'contact_page',
                'Kontak',
                'Silakan hubungi admin untuk pertanyaan lebih lanjut melalui WhatsApp atau email resmi studio.'
            ),
        ]);
    }

    /**
     * Ambil konten dinamis website dari tabel website_contents.
     *
     * @return array{title: string, content: string}
     */
    private function getContent(string $key, string $fallbackTitle, string $fallbackContent): array
    {
        $content = WebsiteContent::query()->where('key', $key)->first();

        return [
            'title' => $content?->title ?? $fallbackTitle,
            'content' => $content?->content ?? $fallbackContent,
        ];
    }

    /**
     * Ambil daftar konten berbasis prefix key lalu kembalikan list title.
     *
     * @param array<int, string> $fallback
     * @return array<int, string>
     */
    private function getSimpleListByPrefix(string $prefix, array $fallback): array
    {
        $items = WebsiteContent::query()
            ->where('key', 'like', $prefix . '%')
            ->orderBy('key')
            ->pluck('title')
            ->filter(fn ($value) => filled($value))
            ->values()
            ->all();

        return $items !== [] ? $items : $fallback;
    }

    /**
     * @return array<int, string>
     */
    private function buildRandomAboutStories(): array
    {
        return collect([
            'UPFotoStudio dimulai dari satu ruang kecil dengan tujuan sederhana: bikin sesi foto terasa nyaman untuk siapa saja.',
            'Seiring waktu, kami berkembang dengan menambah set studio, alur booking online, dan tim kreatif yang lebih solid.',
            'Fokus kami adalah pengalaman pelanggan: proses pemesanan mudah, jadwal jelas, dan hasil dokumentasi yang konsisten.',
            'Banyak klien datang untuk kebutuhan personal, lalu kembali lagi untuk branding bisnis dan konten campaign.',
            'Kami terus menguji konsep background, lighting, dan workflow editing agar hasil selalu relevan dengan tren terbaru.',
            'Setiap sesi dipersiapkan dengan checklist teknis supaya proses foto berjalan efisien tanpa mengurangi kualitas hasil.',
            'Saat ini kami masih terus bereksperimen dan memperbaiki detail layanan agar standar studio semakin matang.',
        ])
            ->shuffle()
            ->take(4)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{label: string, value: string, note: string}>
     */
    private function buildRandomAboutStats(): array
    {
        return collect([
            ['label' => 'Sesi Foto Terselesaikan', 'value' => '3.400+', 'note' => 'Akumulasi data internal (dummy).'],
            ['label' => 'Pelanggan Kembali Booking', 'value' => '68%', 'note' => 'Estimasi repeat order bulanan.'],
            ['label' => 'Pilihan Background Aktif', 'value' => '24', 'note' => 'Termasuk variasi warna basic dan premium.'],
            ['label' => 'Rata-rata Durasi Persiapan', 'value' => '18 menit', 'note' => 'Pra-sesi sampai siap jepret.'],
            ['label' => 'Tim Kreatif Operasional', 'value' => '15 orang', 'note' => 'Fotografer, editor, dan support.'],
            ['label' => 'Tingkat Kepuasan Klien', 'value' => '4.8/5', 'note' => 'Ringkasan feedback after-session.'],
            ['label' => 'Project Branding UMKM', 'value' => '420+', 'note' => 'Produksi konten usaha lokal (dummy).'],
            ['label' => 'Rata-rata Foto Terpilih', 'value' => '38 frame', 'note' => 'Output pilihan per sesi.'],
        ])
            ->shuffle()
            ->take(4)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{title: string, description: string}>
     */
    private function buildRandomAboutValues(): array
    {
        return collect([
            [
                'title' => 'Konsisten Pada Detail',
                'description' => 'Kami menjaga konsistensi tone warna, framing, dan hasil akhir agar kualitas tetap terukur.',
            ],
            [
                'title' => 'Transparan Sejak Awal',
                'description' => 'Informasi harga, durasi, serta output dijelaskan dari awal supaya ekspektasi lebih jelas.',
            ],
            [
                'title' => 'Ramah untuk Pemula',
                'description' => 'Tim membantu arahan pose dan ekspresi, terutama untuk pelanggan yang baru pertama kali studio.',
            ],
            [
                'title' => 'Cepat Beradaptasi',
                'description' => 'Kami fleksibel menyesuaikan konsep visual sesuai kebutuhan personal maupun bisnis.',
            ],
            [
                'title' => 'Aman dan Tertata',
                'description' => 'Workflow booking, pembayaran, dan arsip file disusun rapi agar proses lebih aman.',
            ],
            [
                'title' => 'Kolaboratif',
                'description' => 'Kami terbiasa bekerja bareng make-up artist, stylist, dan tim konten klien.',
            ],
        ])
            ->shuffle()
            ->take(4)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{tag: string, title: string, description: string}>
     */
    private function buildRandomAboutPrograms(): array
    {
        return collect([
            [
                'tag' => 'Komunitas',
                'title' => 'Open Studio Weekend',
                'description' => 'Sesi eksplorasi konsep foto santai untuk komunitas kreatif lokal (dummy).',
            ],
            [
                'tag' => 'Branding',
                'title' => 'Batch Product Shoot',
                'description' => 'Program foto katalog produk skala kecil-menengah untuk UMKM dan brand baru.',
            ],
            [
                'tag' => 'Edukasi',
                'title' => 'Mini Class Lighting Dasar',
                'description' => 'Kelas singkat pengenalan lighting studio untuk pemilik usaha dan content creator.',
            ],
            [
                'tag' => 'Keluarga',
                'title' => 'Family Story Session',
                'description' => 'Sesi foto tematik keluarga dengan set yang disesuaikan usia dan preferensi style.',
            ],
            [
                'tag' => 'Corporate',
                'title' => 'Team Portrait Day',
                'description' => 'Paket dokumentasi portrait tim untuk profile perusahaan dan kebutuhan HR.',
            ],
            [
                'tag' => 'Musiman',
                'title' => 'Campaign Seasonal Setup',
                'description' => 'Setup studio tematik untuk momen Ramadan, akhir tahun, atau promo khusus brand.',
            ],
            [
                'tag' => 'Konten',
                'title' => 'Reels Content Sprint',
                'description' => 'Produksi foto + footage pendek dalam satu sesi untuk kebutuhan media sosial cepat.',
            ],
            [
                'tag' => 'Eksperimen',
                'title' => 'Creative Test Session',
                'description' => 'Sesi uji konsep visual baru untuk pengembangan style dan portfolio internal.',
            ],
        ])
            ->shuffle()
            ->take(6)
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function buildRandomTermExtraItems(): array
    {
        return collect([
            'Perubahan jadwal di hari yang sama mengikuti ketersediaan slot dan dapat dikenakan biaya operasional.',
            'Keterlambatan lebih dari 20 menit dapat mengurangi waktu sesi aktif sesuai jadwal awal.',
            'Permintaan konsep khusus di luar paket standar dapat memerlukan konfirmasi tambahan dari tim.',
            'File hasil foto dikirim sesuai estimasi proses editing yang diinformasikan setelah sesi selesai.',
            'Segala bentuk komplain kualitas wajib diajukan maksimal 2x24 jam setelah file diterima.',
            'Dokumentasi behind-the-scenes hanya dilakukan jika pelanggan memberikan persetujuan sebelumnya.',
            'Penggunaan properti pribadi menjadi tanggung jawab masing-masing pihak selama sesi berlangsung.',
            'Tim berhak menyesuaikan teknis pemotretan demi keamanan alat dan kenyamanan seluruh peserta.',
            'Sesi promosi bundling atau diskon mengikuti periode aktif yang tercantum pada halaman promo.',
            'Permintaan revisi minor hasil editing dibatasi sesuai ketentuan paket yang dipilih.',
            'Apabila terjadi gangguan teknis, tim akan menjadwalkan ulang tanpa biaya tambahan.',
        ])
            ->shuffle()
            ->take(7)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{step: string, title: string, description: string}>
     */
    private function buildRandomTermFlow(): array
    {
        return collect([
            [
                'step' => '01',
                'title' => 'Pilih Paket & Slot',
                'description' => 'Customer memilih paket, studio, tanggal, dan jam yang masih tersedia.',
            ],
            [
                'step' => '02',
                'title' => 'Isi Data Pemesan',
                'description' => 'Data kontak aktif dibutuhkan agar status booking dan invoice mudah dipantau.',
            ],
            [
                'step' => '03',
                'title' => 'Konfirmasi Pembayaran',
                'description' => 'Sistem membuat invoice dan menunggu status pembayaran QRIS berhasil.',
            ],
            [
                'step' => '04',
                'title' => 'Booking Dikonfirmasi',
                'description' => 'Setelah transaksi sukses, status booking berubah menjadi CONFIRMED secara otomatis.',
            ],
            [
                'step' => '05',
                'title' => 'Pelaksanaan Sesi',
                'description' => 'Tim menjalankan sesi sesuai paket dan preferensi yang sudah disepakati.',
            ],
            [
                'step' => '06',
                'title' => 'Pengiriman Hasil',
                'description' => 'Hasil dikirim sesuai SLA paket, termasuk file digital dan benefit tambahan.',
            ],
        ])
            ->shuffle()
            ->take(4)
            ->sortBy('step')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    private function buildRandomTermFaqs(): array
    {
        return collect([
            [
                'question' => 'Apakah DP bisa dikembalikan jika batal?',
                'answer' => 'Pada skenario pembatalan sepihak, DP tidak dapat direfund kecuali ada kondisi force majeure yang disetujui kedua pihak.',
            ],
            [
                'question' => 'Apakah bisa ganti jam setelah booking?',
                'answer' => 'Bisa, selama slot masih tersedia dan pengajuan perubahan dilakukan sebelum batas waktu yang berlaku.',
            ],
            [
                'question' => 'Bagaimana kalau datang terlambat?',
                'answer' => 'Sesi tetap mengikuti slot waktu awal sehingga durasi efektif dapat berkurang menyesuaikan keterlambatan.',
            ],
            [
                'question' => 'Kapan hasil foto dikirim?',
                'answer' => 'Estimasi pengiriman menyesuaikan jenis paket dan antrean editing, biasanya diinformasikan saat sesi selesai.',
            ],
            [
                'question' => 'Apakah boleh membawa konsep sendiri?',
                'answer' => 'Boleh, selama konsep aman, tidak melanggar ketentuan studio, dan diinformasikan sebelum hari pemotretan.',
            ],
            [
                'question' => 'Bagaimana jika pembayaran gagal tapi saldo terpotong?',
                'answer' => 'Silakan kirim bukti transaksi ke admin agar tim dapat melakukan pengecekan manual ke provider pembayaran.',
            ],
            [
                'question' => 'Apakah ada biaya tambahan untuk background khusus?',
                'answer' => 'Untuk beberapa setup khusus, biaya tambahan dapat berlaku dan akan diinformasikan sebelum sesi dimulai.',
            ],
        ])
            ->shuffle()
            ->take(4)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{image: string, title: string, caption: string}>
     */
    private function buildPromoSlides(): array
    {
        $slides = WebsiteContent::query()
            ->where('key', 'like', 'home_promo_slide_%')
            ->orderBy('key')
            ->get()
            ->map(function (WebsiteContent $content, int $index): array {
                $payload = $this->parseJsonPayload($content->content);
                $fallbackImage = asset('assets/images/home/promo/promo-' . (($index % 3) + 1) . '.svg');
                $legacyImage = is_array($payload) ? ($payload['image'] ?? null) : null;
                $legacyCaption = is_array($payload) ? ($payload['caption'] ?? null) : null;

                return [
                    'image' => $this->resolveImageUrl(
                        $content->image_path ?: (is_string($legacyImage) ? $legacyImage : null),
                        $fallbackImage
                    ),
                    'title' => $content->title,
                    'caption' => filled($legacyCaption) ? (string) $legacyCaption : ($content->content ?? ''),
                ];
            })
            ->values()
            ->all();

        if ($slides !== []) {
            return $slides;
        }

        return [
            [
                'image' => asset('assets/images/home/promo/promo-1.svg'),
                'title' => 'First Flipbook Photobooth',
                'caption' => 'Promo design terbaru untuk pengalaman photobooth yang lebih seru.',
            ],
            [
                'image' => asset('assets/images/home/promo/promo-2.svg'),
                'title' => 'Level Up Your Photos',
                'caption' => 'Abadikan momen bersama teman dan keluarga dengan kualitas studio profesional.',
            ],
            [
                'image' => asset('assets/images/home/promo/promo-3.svg'),
                'title' => 'Explore Moments',
                'caption' => 'Pilih tipe sesi favoritmu: couple, group, solo, hingga kebutuhan ID photo.',
            ],
        ];
    }

    /**
     * @return array<int, array{image: string, alt: string}>
     */
    private function buildGalleryPreview(int $limit): array
    {
        $limit = max(1, $limit);

        $previewItems = WebsiteContent::query()
            ->where('key', 'like', 'gallery_item_%')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(function (WebsiteContent $content, int $index): array {
                $payload = $this->parseJsonPayload($content->content);
                $fallbackImage = asset('assets/images/home/gallery/gallery-' . (($index % 5) + 1) . '.svg');
                $legacyImage = is_array($payload) ? ($payload['image'] ?? null) : null;

                return [
                    'image' => $this->resolveImageUrl(
                        $content->image_path ?: (is_string($legacyImage) ? $legacyImage : null),
                        $fallbackImage
                    ),
                    'alt' => filled($content->title)
                        ? $content->title
                        : (
                            is_array($payload) && filled($payload['alt'] ?? null)
                                ? (string) $payload['alt']
                                : 'Galeri ' . ($index + 1)
                        ),
                ];
            })
            ->values()
            ->all();

        if ($previewItems !== []) {
            return $previewItems;
        }

        return array_slice([
            ['image' => asset('assets/images/home/gallery/gallery-1.svg'), 'alt' => 'Galeri 1'],
            ['image' => asset('assets/images/home/gallery/gallery-2.svg'), 'alt' => 'Galeri 2'],
            ['image' => asset('assets/images/home/gallery/gallery-3.svg'), 'alt' => 'Galeri 3'],
            ['image' => asset('assets/images/home/gallery/gallery-4.svg'), 'alt' => 'Galeri 4'],
            ['image' => asset('assets/images/home/gallery/gallery-5.svg'), 'alt' => 'Galeri 5'],
        ], 0, $limit);
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    private function buildFaqItems(): array
    {
        $items = WebsiteContent::query()
            ->where('key', 'like', 'home_faq_%')
            ->orderBy('key')
            ->get()
            ->map(fn (WebsiteContent $content): array => [
                'question' => $content->title,
                'answer' => $content->content ?? '',
            ])
            ->values()
            ->all();

        if ($items !== []) {
            return $items;
        }

        return [
            [
                'question' => 'Bagaimana cara booking studio?',
                'answer' => 'Pilih studio, tanggal, jam mulai, lalu pilih paket layanan. Sistem otomatis menghitung jam selesai dan total pembayaran.',
            ],
            [
                'question' => 'Apakah bisa pembayaran DP?',
                'answer' => 'Bisa. DP dihitung 30% dari total biaya, dengan nilai minimum Rp50.000 sesuai aturan sistem.',
            ],
            [
                'question' => 'Bagaimana jika jadwal bentrok?',
                'answer' => 'Sistem menolak otomatis booking yang overlap pada studio yang sama jika status booking lain masih PENDING_PAYMENT atau CONFIRMED.',
            ],
            [
                'question' => 'Setelah bayar, kapan booking dikonfirmasi?',
                'answer' => 'Booking akan berubah menjadi CONFIRMED secara realtime saat callback pembayaran mengirim status SUCCESS.',
            ],
        ];
    }

    /**
     * @return array<int, array{title: string, image: string, caption: string}>
     */
    private function buildGalleryItems(): array
    {
        $items = WebsiteContent::query()
            ->where('key', 'like', 'gallery_item_%')
            ->orderBy('key')
            ->get()
            ->map(function (WebsiteContent $content, int $index): array {
                $payload = $this->parseJsonPayload($content->content);
                $fallbackImage = asset('assets/images/home/gallery/gallery-' . (($index % 5) + 1) . '.svg');
                $legacyImage = is_array($payload) ? ($payload['image'] ?? null) : null;
                $legacyCaption = is_array($payload) ? ($payload['caption'] ?? null) : null;

                return [
                    'title' => $content->title,
                    'image' => $this->resolveImageUrl(
                        $content->image_path ?: (is_string($legacyImage) ? $legacyImage : null),
                        $fallbackImage
                    ),
                    'caption' => filled($legacyCaption) ? (string) $legacyCaption : ($content->content ?? ''),
                ];
            })
            ->values()
            ->all();

        if ($items !== []) {
            return $items;
        }

        return collect(range(1, 6))
            ->map(fn (int $item): array => [
                'title' => 'Preview Foto ' . $item,
                'image' => asset('assets/images/home/gallery/gallery-' . (($item - 1) % 5 + 1) . '.svg'),
                'caption' => '',
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function parseJsonPayload(?string $content): ?array
    {
        if (!$content) {
            return null;
        }

        $payload = json_decode($content, true);

        return is_array($payload) ? $payload : null;
    }

    private function resolveImageUrl(?string $path, string $fallback): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return $fallback;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, 'data:')
        ) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
