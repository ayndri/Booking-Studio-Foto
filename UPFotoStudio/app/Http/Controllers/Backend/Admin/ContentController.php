<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\WebsiteContentRequest;
use App\Models\WebsiteContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    private const SECTION_ALL = 'all';
    private const SECTION_HOME = 'home';
    private const SECTION_ABOUT = 'about';
    private const SECTION_GALLERY = 'gallery';
    private const SECTION_PRICING = 'pricing';
    private const SECTION_CONTACT = 'contact';
    private const SECTION_TERMS = 'terms';
    private const SECTION_FOOTER = 'footer';

    // Backward compatibility untuk query string lama.
    private const SECTION_SERVICES = 'services';
    private const SECTION_PROMO = 'promo';

    private const HOME_MENU_CAROUSEL = 'carousel';
    private const HOME_MENU_HEADER = 'header';
    private const HOME_MENU_GALLERY = 'gallery';
    private const HOME_MENU_SERVICES = 'services';
    private const HOME_MENU_FAQ = 'faq';
    private const HOME_MENU_FOOTER = 'footer';

    /**
     * @var array<int, string>
     */
    private const HOME_MENUS = [
        self::HOME_MENU_CAROUSEL,
        self::HOME_MENU_HEADER,
        self::HOME_MENU_GALLERY,
        self::HOME_MENU_SERVICES,
        self::HOME_MENU_FAQ,
        self::HOME_MENU_FOOTER,
    ];

    /**
     * @var array<int, string>
     */
    private const SECTIONS = [
        self::SECTION_ALL,
        self::SECTION_HOME,
        self::SECTION_ABOUT,
        self::SECTION_GALLERY,
        self::SECTION_PRICING,
        self::SECTION_CONTACT,
        self::SECTION_TERMS,
        self::SECTION_SERVICES,
        self::SECTION_FOOTER,
        self::SECTION_PROMO,
    ];

    /**
     * Daftar konten website.
     */
    public function index(Request $request): View
    {
        $section = $this->resolveSection($request->query('section'));
        $homeMenu = $this->resolveHomeMenu($request->query('home_menu'), $section);
        $query = WebsiteContent::query()->orderBy('key');

        $this->applySectionFilter($query, $section, $homeMenu);

        $contents = $query->paginate(10)->withQueryString();
        $contents->getCollection()->transform(function (WebsiteContent $content) {
            $legacy = $this->parseLegacyContent($content->content);

            $displayText = $legacy['text'] !== ''
                ? $legacy['text']
                : trim((string) $content->content);

            $imagePath = trim((string) $content->image_path);
            if ($imagePath === '') {
                $imagePath = $legacy['image'];
            }

            $content->setAttribute('admin_label', $this->labelFromKey($content->key));
            $content->setAttribute('admin_text', $displayText);
            $content->setAttribute('admin_image_url', $this->resolveImageUrl($imagePath));

            return $content;
        });

        return view('backend.admin.contents.index', [
            'section' => $section,
            'sectionMeta' => $this->sectionMeta($section, $homeMenu),
            'homeMenu' => $homeMenu,
            'contents' => $contents,
        ]);
    }

    /**
     * Form tambah konten.
     */
    public function create(Request $request): View
    {
        $section = $this->resolveSection($request->query('section'));
        if ($section === self::SECTION_ALL) {
            $section = self::SECTION_HOME;
        }

        $homeMenu = $this->resolveHomeMenu($request->query('home_menu'), $section);
        $contentTypes = $this->contentTypeOptions($section, $homeMenu);
        $defaultContentType = $contentTypes[0]['value'] ?? '';

        return view('backend.admin.contents.create', [
            'section' => $section,
            'sectionMeta' => $this->sectionMeta($section, $homeMenu),
            'homeMenu' => $homeMenu,
            'contentTypes' => $contentTypes,
            'requiresTypeSelection' => count($contentTypes) > 1,
            'defaultContentType' => $defaultContentType,
        ]);
    }

    /**
     * Simpan konten baru.
     */
    public function store(WebsiteContentRequest $request): RedirectResponse
    {
        $section = $this->resolveSection($request->input('section'));
        $homeMenu = $this->resolveHomeMenu($request->input('home_menu'), $section);

        $typeMeta = $this->resolveContentTypeMeta($section, (string) $request->input('content_type'), $homeMenu);

        if ($typeMeta === null) {
            return back()
                ->withErrors(['content_type' => 'Jenis konten wajib dipilih.'])
                ->withInput();
        }

        $key = $this->buildKeyFromTypeMeta($typeMeta);
        $content = WebsiteContent::query()->where('key', $key)->first() ?? new WebsiteContent(['key' => $key]);
        $requiresImage = (bool) ($typeMeta['requires_image'] ?? false);

        $legacy = $this->parseLegacyContent($content->content);
        $currentImagePath = trim((string) $content->image_path);
        if ($currentImagePath === '') {
            $currentImagePath = $legacy['image'];
        }

        $newImagePath = $this->storeImage($request);
        if ($newImagePath !== null) {
            if ($currentImagePath !== '') {
                $this->deleteImage($currentImagePath);
            }
            $currentImagePath = $newImagePath;
        }

        if ($requiresImage && $currentImagePath === '') {
            return back()
                ->withErrors(['image' => 'Gambar wajib diisi untuk jenis konten ini.'])
                ->withInput();
        }

        $content->title = trim((string) $request->input('title'));
        $content->content = $this->normalizeText($request->input('text_content'));
        $content->image_path = $currentImagePath !== '' ? $currentImagePath : null;
        $content->save();

        return redirect()
            ->route('admin.contents.index', $this->buildContentRouteParams($section, $homeMenu))
            ->with('success', 'Konten berhasil disimpan.');
    }

    /**
     * Form edit konten.
     */
    public function edit(Request $request, WebsiteContent $content): View
    {
        $section = $this->resolveSection($request->query('section') ?: $this->detectSectionFromKey($content->key));
        $homeMenu = $this->resolveHomeMenu(
            $request->query('home_menu') ?: $this->detectHomeMenuFromKey($content->key),
            $section
        );
        $legacy = $this->parseLegacyContent($content->content);

        $imagePath = trim((string) $content->image_path);
        if ($imagePath === '') {
            $imagePath = $legacy['image'];
        }

        $textValue = $legacy['text'] !== ''
            ? $legacy['text']
            : trim((string) $content->content);

        return view('backend.admin.contents.edit', [
            'section' => $section,
            'sectionMeta' => $this->sectionMeta($section, $homeMenu),
            'homeMenu' => $homeMenu,
            'content' => $content,
            'contentLabel' => $this->labelFromKey($content->key),
            'textValue' => $textValue,
            'imageUrl' => $this->resolveImageUrl($imagePath),
        ]);
    }

    /**
     * Update konten.
     */
    public function update(WebsiteContentRequest $request, WebsiteContent $content): RedirectResponse
    {
        $section = $this->resolveSection($request->input('section') ?: $this->detectSectionFromKey($content->key));
        $homeMenu = $this->resolveHomeMenu(
            $request->input('home_menu') ?: $this->detectHomeMenuFromKey($content->key),
            $section
        );
        $legacy = $this->parseLegacyContent($content->content);

        $currentImagePath = trim((string) $content->image_path);
        if ($currentImagePath === '') {
            $currentImagePath = $legacy['image'];
        }

        if ($request->boolean('remove_image') && $currentImagePath !== '') {
            $this->deleteImage($currentImagePath);
            $currentImagePath = '';
        }

        $newImagePath = $this->storeImage($request);
        if ($newImagePath !== null) {
            if ($currentImagePath !== '') {
                $this->deleteImage($currentImagePath);
            }
            $currentImagePath = $newImagePath;
        }

        if ($this->keyRequiresImage($content->key) && $currentImagePath === '') {
            return back()
                ->withErrors(['image' => 'Gambar wajib ada untuk konten ini.'])
                ->withInput();
        }

        $content->update([
            'title' => trim((string) $request->input('title')),
            'content' => $this->normalizeText($request->input('text_content')),
            'image_path' => $currentImagePath !== '' ? $currentImagePath : null,
        ]);

        return redirect()
            ->route('admin.contents.index', $this->buildContentRouteParams($section, $homeMenu))
            ->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Hapus konten.
     */
    public function destroy(Request $request, WebsiteContent $content): RedirectResponse
    {
        $section = $this->resolveSection($request->input('section') ?: $this->detectSectionFromKey($content->key));
        $homeMenu = $this->resolveHomeMenu(
            $request->input('home_menu') ?: $this->detectHomeMenuFromKey($content->key),
            $section
        );

        $this->deleteImageByContent($content);
        $content->delete();

        return redirect()
            ->route('admin.contents.index', $this->buildContentRouteParams($section, $homeMenu))
            ->with('success', 'Konten berhasil dihapus.');
    }

    private function resolveSection(?string $section): string
    {
        $normalized = strtolower(trim((string) $section));

        if (!in_array($normalized, self::SECTIONS, true)) {
            return self::SECTION_ALL;
        }

        return match ($normalized) {
            self::SECTION_PROMO,
            self::SECTION_SERVICES => self::SECTION_HOME,
            default => $normalized,
        };
    }

    /**
     * @return array{title: string, description: string, addLabel: string}
     */
    private function sectionMeta(string $section, ?string $homeMenu = null): array
    {
        if ($section === self::SECTION_HOME && $homeMenu !== null) {
            return match ($homeMenu) {
                self::HOME_MENU_CAROUSEL => [
                    'title' => 'Konten Beranda - Carousel',
                    'description' => 'Kelola slide carousel yang tampil di bagian atas beranda.',
                    'addLabel' => 'Tambah Slide Carousel',
                ],
                self::HOME_MENU_HEADER => [
                    'title' => 'Konten Beranda - Header',
                    'description' => 'Kelola judul utama beranda dan poin keunggulan booking.',
                    'addLabel' => 'Tambah Konten Header Beranda',
                ],
                self::HOME_MENU_GALLERY => [
                    'title' => 'Konten Beranda - Galeri',
                    'description' => 'Kelola judul galeri beranda dan item foto yang ditampilkan.',
                    'addLabel' => 'Tambah Konten Galeri Beranda',
                ],
                self::HOME_MENU_SERVICES => [
                    'title' => 'Konten Beranda - Layanan',
                    'description' => 'Kelola judul dan tombol CTA untuk section layanan di beranda.',
                    'addLabel' => 'Tambah Konten Layanan Beranda',
                ],
                self::HOME_MENU_FAQ => [
                    'title' => 'Konten Beranda - FAQ',
                    'description' => 'Kelola heading FAQ dan daftar pertanyaan yang tampil di beranda.',
                    'addLabel' => 'Tambah Konten FAQ Beranda',
                ],
                default => [
                    'title' => 'Konten Beranda',
                    'description' => 'Kelola konten beranda: carousel, galeri, layanan, FAQ, dan elemen utama beranda.',
                    'addLabel' => 'Tambah Konten Beranda',
                ],
            };
        }

        if ($section === self::SECTION_FOOTER && $homeMenu === self::HOME_MENU_FOOTER) {
            return [
                'title' => 'Konten Beranda - Footer',
                'description' => 'Kelola konten footer yang tampil di seluruh halaman website.',
                'addLabel' => 'Tambah Konten Footer',
            ];
        }

        return match ($section) {
            self::SECTION_HOME => [
                'title' => 'Konten Beranda',
                'description' => 'Kelola konten beranda: carousel, galeri, layanan, FAQ, dan elemen utama beranda.',
                'addLabel' => 'Tambah Konten Beranda',
            ],
            self::SECTION_ABOUT => [
                'title' => 'Tentang Kami',
                'description' => 'Kelola konten halaman Tentang Kami.',
                'addLabel' => 'Tambah Konten Tentang Kami',
            ],
            self::SECTION_GALLERY => [
                'title' => 'Galeri Keseluruhan',
                'description' => 'Kelola konten halaman galeri dan item foto yang tampil di beranda maupun halaman galeri.',
                'addLabel' => 'Tambah Konten Galeri',
            ],
            self::SECTION_PRICING => [
                'title' => 'Paket Harga',
                'description' => 'Kelola konten halaman Paket Harga.',
                'addLabel' => 'Tambah Konten Paket Harga',
            ],
            self::SECTION_CONTACT => [
                'title' => 'Kontak',
                'description' => 'Kelola konten halaman Kontak.',
                'addLabel' => 'Tambah Konten Kontak',
            ],
            self::SECTION_TERMS => [
                'title' => 'Lainnya (S&K)',
                'description' => 'Kelola konten Syarat & Ketentuan.',
                'addLabel' => 'Tambah Konten S&K',
            ],
            self::SECTION_FOOTER => [
                'title' => 'Konten Footer',
                'description' => 'Kelola konten footer website.',
                'addLabel' => 'Tambah Konten Footer',
            ],
            default => [
                'title' => 'Konten Website',
                'description' => 'Daftar seluruh konten website.',
                'addLabel' => 'Tambah Konten',
            ],
        };
    }

    private function applySectionFilter(Builder $query, string $section, ?string $homeMenu = null): void
    {
        if ($section === self::SECTION_ALL) {
            return;
        }

        if ($section === self::SECTION_HOME) {
            if ($homeMenu === self::HOME_MENU_CAROUSEL) {
                $query->where('key', 'like', 'home_promo_slide_%');
                return;
            }

            if ($homeMenu === self::HOME_MENU_HEADER) {
                $query->where(function (Builder $subQuery) {
                    $subQuery
                        ->whereIn('key', ['home_hero', 'home_why_choose'])
                        ->orWhere('key', 'like', 'home_why_choose_item_%');
                });
                return;
            }

            if ($homeMenu === self::HOME_MENU_GALLERY) {
                $query->where(function (Builder $subQuery) {
                    $subQuery
                        ->where('key', 'home_gallery_section')
                        ->orWhere('key', 'like', 'gallery_item_%');
                });
                return;
            }

            if ($homeMenu === self::HOME_MENU_SERVICES) {
                $query->where('key', 'home_service_section');
                return;
            }

            if ($homeMenu === self::HOME_MENU_FAQ) {
                $query->where(function (Builder $subQuery) {
                    $subQuery
                        ->where('key', 'home_faq_section')
                        ->orWhere('key', 'like', 'home_faq_%');
                });
                return;
            }

            $query->where('key', 'like', 'home_%');
            return;
        }

        if ($section === self::SECTION_ABOUT) {
            $query->where('key', 'about_page');
            return;
        }

        if ($section === self::SECTION_GALLERY) {
            $query->where(function (Builder $subQuery) {
                $subQuery
                    ->where('key', 'gallery_page')
                    ->orWhere('key', 'like', 'gallery_item_%');
            });
            return;
        }

        if ($section === self::SECTION_PRICING) {
            $query->where('key', 'pricing_page');
            return;
        }

        if ($section === self::SECTION_CONTACT) {
            $query->where('key', 'contact_page');
            return;
        }

        if ($section === self::SECTION_TERMS) {
            $query->where(function (Builder $subQuery) {
                $subQuery
                    ->where('key', 'terms_page')
                    ->orWhere('key', 'like', 'terms_item_%');
            });
            return;
        }

        if ($section === self::SECTION_FOOTER) {
            $query->where('key', 'like', 'footer_%');
        }
    }

    /**
     * @return array<int, array{value: string, label: string, mode: string, key?: string, prefix?: string, requires_image: bool}>
     */
    private function contentTypeOptions(string $section, ?string $homeMenu = null): array
    {
        if ($section === self::SECTION_HOME) {
            $options = [
                ['value' => 'home_hero', 'label' => 'Hero Beranda', 'mode' => 'single', 'key' => 'home_hero', 'requires_image' => false],
                ['value' => 'home_promo_slide_new', 'label' => 'Slide Carousel Beranda Baru', 'mode' => 'repeat', 'prefix' => 'home_promo_slide_', 'requires_image' => true],
                ['value' => 'home_gallery_section', 'label' => 'Header Galeri Beranda', 'mode' => 'single', 'key' => 'home_gallery_section', 'requires_image' => false],
                ['value' => 'gallery_item_new', 'label' => 'Item Galeri Baru', 'mode' => 'repeat', 'prefix' => 'gallery_item_', 'requires_image' => true],
                ['value' => 'home_service_section', 'label' => 'Header Layanan Beranda', 'mode' => 'single', 'key' => 'home_service_section', 'requires_image' => false],
                ['value' => 'home_why_choose', 'label' => 'Header Kenapa Pilih Kami', 'mode' => 'single', 'key' => 'home_why_choose', 'requires_image' => false],
                ['value' => 'home_why_choose_item_new', 'label' => 'Poin Kenapa Pilih Kami Baru', 'mode' => 'repeat', 'prefix' => 'home_why_choose_item_', 'requires_image' => false],
                ['value' => 'home_faq_section', 'label' => 'Header FAQ Beranda', 'mode' => 'single', 'key' => 'home_faq_section', 'requires_image' => false],
                ['value' => 'home_faq_new', 'label' => 'FAQ Beranda Baru', 'mode' => 'repeat', 'prefix' => 'home_faq_', 'requires_image' => false],
            ];

            if ($homeMenu === null) {
                return $options;
            }

            $allowedValues = match ($homeMenu) {
                self::HOME_MENU_CAROUSEL => ['home_promo_slide_new'],
                self::HOME_MENU_HEADER => ['home_hero', 'home_why_choose', 'home_why_choose_item_new'],
                self::HOME_MENU_GALLERY => ['home_gallery_section', 'gallery_item_new'],
                self::HOME_MENU_SERVICES => ['home_service_section'],
                self::HOME_MENU_FAQ => ['home_faq_section', 'home_faq_new'],
                default => array_column($options, 'value'),
            };

            return array_values(array_filter(
                $options,
                fn (array $option): bool => in_array($option['value'], $allowedValues, true)
            ));
        }

        if ($section === self::SECTION_ABOUT) {
            return [
                ['value' => 'about_page', 'label' => 'Halaman Tentang Kami', 'mode' => 'single', 'key' => 'about_page', 'requires_image' => false],
            ];
        }

        if ($section === self::SECTION_GALLERY) {
            return [
                ['value' => 'gallery_page', 'label' => 'Halaman Galeri', 'mode' => 'single', 'key' => 'gallery_page', 'requires_image' => false],
                ['value' => 'gallery_item_new', 'label' => 'Item Galeri Baru', 'mode' => 'repeat', 'prefix' => 'gallery_item_', 'requires_image' => true],
            ];
        }

        if ($section === self::SECTION_PRICING) {
            return [
                ['value' => 'pricing_page', 'label' => 'Halaman Paket Harga', 'mode' => 'single', 'key' => 'pricing_page', 'requires_image' => false],
            ];
        }

        if ($section === self::SECTION_CONTACT) {
            return [
                ['value' => 'contact_page', 'label' => 'Halaman Kontak', 'mode' => 'single', 'key' => 'contact_page', 'requires_image' => false],
            ];
        }

        if ($section === self::SECTION_TERMS) {
            return [
                ['value' => 'terms_page', 'label' => 'Halaman Syarat & Ketentuan', 'mode' => 'single', 'key' => 'terms_page', 'requires_image' => false],
                ['value' => 'terms_item_new', 'label' => 'Poin Syarat & Ketentuan Baru', 'mode' => 'repeat', 'prefix' => 'terms_item_', 'requires_image' => false],
            ];
        }

        if ($section === self::SECTION_FOOTER) {
            return [
                ['value' => 'footer_brand', 'label' => 'Brand Footer', 'mode' => 'single', 'key' => 'footer_brand', 'requires_image' => false],
                ['value' => 'footer_description', 'label' => 'Deskripsi Footer', 'mode' => 'single', 'key' => 'footer_description', 'requires_image' => false],
                ['value' => 'footer_contact', 'label' => 'Kontak Footer', 'mode' => 'single', 'key' => 'footer_contact', 'requires_image' => false],
                ['value' => 'footer_copyright', 'label' => 'Copyright Footer', 'mode' => 'single', 'key' => 'footer_copyright', 'requires_image' => false],
            ];
        }

        return [];
    }

    /**
     * @return array{value: string, label: string, mode: string, key?: string, prefix?: string, requires_image: bool}|null
     */
    private function resolveContentTypeMeta(string $section, string $contentType, ?string $homeMenu = null): ?array
    {
        foreach ($this->contentTypeOptions($section, $homeMenu) as $option) {
            if ($option['value'] === $contentType) {
                return $option;
            }
        }

        return null;
    }

    /**
     * @param array{mode: string, key?: string, prefix?: string} $typeMeta
     */
    private function buildKeyFromTypeMeta(array $typeMeta): string
    {
        if ($typeMeta['mode'] === 'single') {
            return (string) ($typeMeta['key'] ?? '');
        }

        return $this->nextKeyByPrefix((string) ($typeMeta['prefix'] ?? ''));
    }

    private function nextKeyByPrefix(string $prefix): string
    {
        $maxNumber = WebsiteContent::query()
            ->where('key', 'like', $prefix . '%')
            ->pluck('key')
            ->map(fn (string $key): int => $this->extractNumberFromKey($key, $prefix))
            ->max() ?? 0;

        return $prefix . ($maxNumber + 1);
    }

    private function extractNumberFromKey(string $key, string $prefix): int
    {
        if (!str_starts_with($key, $prefix)) {
            return 0;
        }

        $suffix = substr($key, strlen($prefix));

        return ctype_digit($suffix) ? (int) $suffix : 0;
    }

    private function detectSectionFromKey(string $key): string
    {
        if (str_starts_with($key, 'home_')) {
            return self::SECTION_HOME;
        }

        if ($key === 'about_page') {
            return self::SECTION_ABOUT;
        }

        if (
            $key === 'gallery_page'
            || str_starts_with($key, 'gallery_item_')
        ) {
            return self::SECTION_GALLERY;
        }

        if ($key === 'pricing_page') {
            return self::SECTION_PRICING;
        }

        if ($key === 'contact_page') {
            return self::SECTION_CONTACT;
        }

        if ($key === 'terms_page' || str_starts_with($key, 'terms_item_')) {
            return self::SECTION_TERMS;
        }

        if (str_starts_with($key, 'footer_')) {
            return self::SECTION_FOOTER;
        }

        return self::SECTION_ALL;
    }

    private function resolveHomeMenu(?string $homeMenu, string $section): ?string
    {
        $normalized = strtolower(trim((string) $homeMenu));

        if ($normalized === '' || !in_array($normalized, self::HOME_MENUS, true)) {
            return null;
        }

        if ($section === self::SECTION_HOME && $normalized === self::HOME_MENU_FOOTER) {
            return null;
        }

        if ($section === self::SECTION_FOOTER && $normalized !== self::HOME_MENU_FOOTER) {
            return null;
        }

        if (!in_array($section, [self::SECTION_HOME, self::SECTION_FOOTER], true)) {
            return null;
        }

        return $normalized;
    }

    private function detectHomeMenuFromKey(string $key): ?string
    {
        if (str_starts_with($key, 'home_promo_slide_')) {
            return self::HOME_MENU_CAROUSEL;
        }

        if (
            in_array($key, ['home_hero', 'home_why_choose'], true)
            || str_starts_with($key, 'home_why_choose_item_')
        ) {
            return self::HOME_MENU_HEADER;
        }

        if ($key === 'home_gallery_section' || str_starts_with($key, 'gallery_item_')) {
            return self::HOME_MENU_GALLERY;
        }

        if ($key === 'home_service_section') {
            return self::HOME_MENU_SERVICES;
        }

        if ($key === 'home_faq_section' || str_starts_with($key, 'home_faq_')) {
            return self::HOME_MENU_FAQ;
        }

        if (str_starts_with($key, 'footer_')) {
            return self::HOME_MENU_FOOTER;
        }

        return null;
    }

    /**
     * @return array{section: string, home_menu?: string}
     */
    private function buildContentRouteParams(string $section, ?string $homeMenu = null): array
    {
        $params = ['section' => $section];

        if ($homeMenu !== null) {
            $params['home_menu'] = $homeMenu;
        }

        return $params;
    }

    private function labelFromKey(string $key): string
    {
        if ($key === 'home_hero') {
            return 'Hero Beranda';
        }

        if ($key === 'home_gallery_section') {
            return 'Header Galeri Beranda';
        }

        if ($key === 'gallery_page') {
            return 'Halaman Galeri';
        }

        if ($key === 'home_service_section') {
            return 'Header Layanan Beranda';
        }

        if ($key === 'home_faq_section') {
            return 'Header FAQ Beranda';
        }

        if ($key === 'about_page') {
            return 'Halaman Tentang Kami';
        }

        if ($key === 'pricing_page') {
            return 'Halaman Paket Harga';
        }

        if ($key === 'terms_page') {
            return 'Halaman Syarat & Ketentuan';
        }

        if ($key === 'contact_page') {
            return 'Halaman Kontak';
        }

        if ($key === 'home_why_choose') {
            return 'Header Kenapa Pilih Kami';
        }

        if ($key === 'footer_brand') {
            return 'Brand Footer';
        }

        if ($key === 'footer_description') {
            return 'Deskripsi Footer';
        }

        if ($key === 'footer_contact') {
            return 'Kontak Footer';
        }

        if ($key === 'footer_copyright') {
            return 'Copyright Footer';
        }

        if (preg_match('/^gallery_item_(\d+)$/', $key, $matches) === 1) {
            return 'Item Galeri #' . $matches[1];
        }

        if (preg_match('/^home_gallery_preview_(\d+)$/', $key, $matches) === 1) {
            return 'Preview Galeri Beranda #' . $matches[1];
        }

        if (preg_match('/^home_promo_slide_(\d+)$/', $key, $matches) === 1) {
            return 'Slide Promo #' . $matches[1];
        }

        if (preg_match('/^home_faq_(\d+)$/', $key, $matches) === 1) {
            return 'FAQ Beranda #' . $matches[1];
        }

        if (preg_match('/^home_why_choose_item_(\d+)$/', $key, $matches) === 1) {
            return 'Poin Kenapa Pilih Kami #' . $matches[1];
        }

        if (preg_match('/^terms_item_(\d+)$/', $key, $matches) === 1) {
            return 'Poin S&K #' . $matches[1];
        }

        return ucwords(str_replace('_', ' ', $key));
    }

    /**
     * @return array{text: string, image: string}
     */
    private function parseLegacyContent(?string $content): array
    {
        $text = trim((string) $content);
        $image = '';

        $payload = json_decode((string) $content, true);
        if (!is_array($payload)) {
            return ['text' => $text, 'image' => $image];
        }

        $image = trim((string) ($payload['image'] ?? ''));
        $text = trim((string) ($payload['caption'] ?? $payload['text'] ?? $payload['description'] ?? $payload['alt'] ?? ''));

        return ['text' => $text, 'image' => $image];
    }

    private function keyRequiresImage(string $key): bool
    {
        return str_starts_with($key, 'home_promo_slide_')
            || str_starts_with($key, 'gallery_item_');
    }

    private function normalizeText(mixed $text): ?string
    {
        $value = trim((string) $text);

        return $value === '' ? null : $value;
    }

    private function storeImage(WebsiteContentRequest $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $storedPath = (string) $request->file('image')->store('website/contents', 'public');

        return 'storage/' . ltrim($storedPath, '/');
    }

    private function deleteImageByContent(WebsiteContent $content): void
    {
        $path = trim((string) $content->image_path);

        if ($path === '') {
            $legacy = $this->parseLegacyContent($content->content);
            $path = $legacy['image'];
        }

        $this->deleteImage($path);
    }

    private function deleteImage(?string $path): void
    {
        $path = trim((string) $path);

        if (
            $path === ''
            || str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, 'data:')
        ) {
            return;
        }

        if (str_starts_with($path, 'storage/')) {
            $path = ltrim(substr($path, strlen('storage/')), '/');
        }

        Storage::disk('public')->delete($path);
    }

    private function resolveImageUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
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
