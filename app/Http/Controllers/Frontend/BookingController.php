<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreBookingRequest;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Models\ServicePackage;
use App\Models\Studio;
use App\Services\Bookings\BookingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Halaman booking diarahkan ke halaman paket harga.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('frontend.pricing');
    }

    /**
     * Halaman detail paket + pemilihan tanggal dan jam.
     */
    public function packageDetail(Request $request, ServicePackage $servicePackage): View
    {
        $servicePackage->load('studio');

        abort_if(!$servicePackage->is_active || !$servicePackage->studio?->is_active, 404);

        $selectedDate = $this->normalizeDate($request->query('date'));
        $monthCursor = $this->resolveMonthCursor($request->query('month'), $selectedDate);

        $availableSlots = $this->buildAvailableSlots($servicePackage, $selectedDate);
        $selectedTime = (string) $request->query('time', '');

        $hasSelectedAvailableSlot = collect($availableSlots)
            ->contains(fn ($slot) => $slot['time'] === $selectedTime && $slot['available']);

        if (!$hasSelectedAvailableSlot) {
            $selectedTime = '';
        }

        return view('frontend.booking.package-detail', [
            'servicePackage' => $servicePackage,
            'packageImage' => $servicePackage->image_url,
            'peopleLabel' => $this->inferPeopleLabel($servicePackage->name),
            'benefits' => $this->packageBenefits($servicePackage),
            'monthCursor' => $monthCursor,
            'prevMonth' => $monthCursor->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $monthCursor->copy()->addMonth()->format('Y-m'),
            'calendarWeeks' => $this->buildCalendarWeeks($monthCursor, $selectedDate),
            'selectedDate' => $selectedDate,
            'selectedTime' => $selectedTime,
            'availableSlots' => $availableSlots,
        ]);
    }

    /**
     * Halaman ringkasan order + add-on + data pemesan.
     */
    public function order(Request $request): View
    {
        /** @var array{package_id: int, booking_date: string, start_time: string} $validated */
        $validated = $request->validate([
            'package_id' => ['required', 'integer', 'exists:service_packages,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        $servicePackage = ServicePackage::query()
            ->with('studio')
            ->where('is_active', true)
            ->findOrFail($validated['package_id']);

        $availableSlots = $this->buildAvailableSlots($servicePackage, $validated['booking_date']);
        $selectedSlot = collect($availableSlots)
            ->first(fn ($slot) => $slot['time'] === $validated['start_time'] && $slot['available']);

        if (!$selectedSlot) {
            return redirect()
                ->route('frontend.booking.package-detail', [
                    'servicePackage' => $servicePackage->id,
                    'date' => $validated['booking_date'],
                ])
                ->withErrors(['start_time' => 'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih ulang.']);
        }

        return view('frontend.booking.order', [
            'servicePackage' => $servicePackage,
            'packageImage' => $servicePackage->image_url,
            'peopleLabel' => $this->inferPeopleLabel($servicePackage->name),
            'benefits' => $this->packageBenefits($servicePackage),
            'bookingDate' => $validated['booking_date'],
            'startTime' => $validated['start_time'],
            'bookingDateText' => Carbon::parse($validated['booking_date'])->translatedFormat('j F Y'),
            'addOnCatalog' => $this->getAddOnCatalog(),
        ]);
    }

    /**
     * Simpan booking baru beserta transaksi pembayaran.
     */
    public function store(StoreBookingRequest $request, BookingService $bookingService): RedirectResponse
    {
        $data = $request->validated();

        $selectedAddOns = $this->resolveSelectedAddOns($request->input('add_ons', []));
        $addOnAmount = (int) $selectedAddOns->sum(fn ($addOn) => $addOn['subtotal']);

        $data['add_on_amount'] = $addOnAmount;

        if ($selectedAddOns->isNotEmpty()) {
            $addOnNote = 'Add-ons: ' . $selectedAddOns
                ->map(fn ($addOn) => sprintf('%s x%d', $addOn['name'], $addOn['qty']))
                ->implode(', ');

            $existingNotes = trim((string) ($data['notes'] ?? ''));
            $data['notes'] = $existingNotes === ''
                ? $addOnNote
                : $existingNotes . ' | ' . $addOnNote;
        }

        $extraNotes = [];

        if (!empty($data['background_choice'])) {
            $extraNotes[] = 'Background: ' . $data['background_choice'];
        }

        if (!empty($data['social_consent'])) {
            $extraNotes[] = 'Social Upload: ' . ($data['social_consent'] === 'ALLOW' ? 'Boleh' : 'Tidak Boleh');
        }

        if (!empty($extraNotes)) {
            $existingNotes = trim((string) ($data['notes'] ?? ''));
            $data['notes'] = $existingNotes === ''
                ? implode(' | ', $extraNotes)
                : $existingNotes . ' | ' . implode(' | ', $extraNotes);
        }

        $result = $bookingService->createBooking($data);

        /** @var PaymentTransaction $transaction */
        $transaction = $result['transaction'];

        $paymentUrl = $this->resolvePaymentUrl(
            $result['payment_url'] ?? null,
            $transaction->invoice_number
        );

        return redirect()
            ->to($paymentUrl)
            ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran QRIS.');
    }

    /**
     * Halaman status pembayaran per invoice.
     */
    public function status(string $invoiceNumber): View
    {
        $transaction = PaymentTransaction::query()
            ->with(['booking.guest', 'booking.studio', 'booking.servicePackage'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();

        return view('frontend.booking.status', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Download invoice booking dalam format PDF.
     */
    public function invoice(string $invoiceNumber)
    {
        $transaction = PaymentTransaction::query()
            ->with(['booking.guest', 'booking.studio', 'booking.servicePackage'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.invoices.invoice', [
            'transaction' => $transaction,
            'booking' => $transaction->booking,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $transaction->invoice_number . '.pdf');
    }

    /**
     * Endpoint helper untuk mengambil paket aktif berdasarkan studio.
     */
    public function packagesByStudio(Studio $studio): JsonResponse
    {
        $packages = ServicePackage::query()
            ->where('studio_id', $studio->id)
            ->where('is_active', true)
            ->orderBy('price')
            ->get(['id', 'name', 'price', 'duration_minutes']);

        return response()->json([
            'studio_id' => $studio->id,
            'packages' => $packages,
        ]);
    }

    /**
     * AJAX: kembalikan slot tersedia untuk paket + tanggal tertentu.
     */
    public function slotsJson(ServicePackage $servicePackage, Request $request): JsonResponse
    {
        $date = $this->normalizeDate($request->query('date'));
        return response()->json($this->buildAvailableSlots($servicePackage, $date));
    }

    /**
     * Normalisasi tanggal agar valid dan tidak bisa tanggal lewat.
     */
    private function normalizeDate(?string $date): string
    {
        if (!$date) {
            return now()->toDateString();
        }

        try {
            $parsed = Carbon::parse($date)->startOfDay();
        } catch (\Throwable) {
            return now()->toDateString();
        }

        if ($parsed->lt(now()->startOfDay())) {
            return now()->toDateString();
        }

        return $parsed->toDateString();
    }

    /**
     * Ambil cursor bulan untuk rendering kalender.
     */
    private function resolveMonthCursor(?string $monthInput, string $selectedDate): Carbon
    {
        if ($monthInput && preg_match('/^\d{4}-\d{2}$/', $monthInput) === 1) {
            try {
                return Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
            } catch (\Throwable) {
                // fallback ke selected date.
            }
        }

        return Carbon::parse($selectedDate)->startOfMonth();
    }

    /**
     * Normalisasi URL pembayaran agar fallback ke status invoice bila URL gateway tidak valid.
     */
    private function resolvePaymentUrl(mixed $paymentUrl, string $invoiceNumber): string
    {
        $fallbackUrl = route('frontend.booking.status', $invoiceNumber);

        if (!is_string($paymentUrl)) {
            return $fallbackUrl;
        }

        $trimmed = trim($paymentUrl);

        if ($trimmed === '') {
            return $fallbackUrl;
        }

        $isAbsoluteHttp = filter_var($trimmed, FILTER_VALIDATE_URL) !== false
            && preg_match('/^https?:\/\//i', $trimmed) === 1;

        if ($isAbsoluteHttp || str_starts_with($trimmed, '/')) {
            return $trimmed;
        }

        return $fallbackUrl;
    }

    /**
     * Render data kalender berbasis minggu.
     *
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function buildCalendarWeeks(Carbon $monthCursor, string $selectedDate): array
    {
        $today = now()->startOfDay();
        $selected = Carbon::parse($selectedDate)->startOfDay();

        $start = $monthCursor->copy()->startOfWeek(Carbon::MONDAY);
        $end = $monthCursor->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $weeks = [];
        $weekBuffer = [];

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $weekBuffer[] = [
                'day' => $day->day,
                'date' => $day->toDateString(),
                'is_current_month' => $day->month === $monthCursor->month,
                'is_today' => $day->equalTo($today),
                'is_selected' => $day->equalTo($selected),
                'is_past' => $day->lt($today),
            ];

            if (count($weekBuffer) === 7) {
                $weeks[] = $weekBuffer;
                $weekBuffer = [];
            }
        }

        return $weeks;
    }

    /**
     * Bangun daftar slot jam dengan pengecekan bentrok booking.
     *
     * @return array<int, array{time: string, available: bool}>
     */
    private function buildAvailableSlots(ServicePackage $servicePackage, string $bookingDate): array
    {
        $openAt = Carbon::createFromFormat('Y-m-d H:i', $bookingDate . ' 10:00');
        $closeAt = Carbon::createFromFormat('Y-m-d H:i', $bookingDate . ' 21:00');

        $existingRanges = Booking::query()
            ->where('studio_id', $servicePackage->studio_id)
            ->whereDate('booking_date', $bookingDate)
            ->whereIn('status', [Booking::STATUS_PENDING_PAYMENT, Booking::STATUS_CONFIRMED])
            ->get(['start_time', 'end_time'])
            ->map(function ($booking) use ($bookingDate) {
                return [
                    'start' => Carbon::createFromFormat('Y-m-d H:i:s', $bookingDate . ' ' . $booking->start_time),
                    'end' => Carbon::createFromFormat('Y-m-d H:i:s', $bookingDate . ' ' . $booking->end_time),
                ];
            });

        $now = now();
        $slots = [];

        for ($slotStart = $openAt->copy(); $slotStart->lt($closeAt); $slotStart->addMinutes(30)) {
            $slotEnd = $slotStart->copy()->addMinutes($servicePackage->duration_minutes);

            $isWithinOperatingHours = $slotEnd->lte($closeAt);
            $isPast = $slotStart->lte($now);

            $hasOverlap = $existingRanges->contains(function ($range) use ($slotStart, $slotEnd) {
                return $range['start']->lt($slotEnd) && $range['end']->gt($slotStart);
            });

            $slots[] = [
                'time' => $slotStart->format('H:i'),
                'available' => $isWithinOperatingHours && !$isPast && !$hasOverlap,
            ];
        }

        return $slots;
    }

    /**
     * Ambil katalog add-on yang ditampilkan di halaman order.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getAddOnCatalog(): array
    {
        return [
            [
                'id' => 'extra_print',
                'name' => 'Extra Print',
                'price' => 20000,
                'unit' => '/pcs',
                'description' => 'Tambahan printed photo ukuran 4R (10,2 x 15,2 cm).',
                'icon' => 'PR',
            ],
            [
                'id' => 'jas_hitam',
                'name' => 'Jas Hitam',
                'price' => 15000,
                'unit' => '/costume',
                'description' => 'Jas hitam untuk kebutuhan foto formal ukuran M/L/XL.',
                'icon' => 'JS',
            ],
            [
                'id' => 'costume',
                'name' => 'Costume',
                'price' => 15000,
                'unit' => '/costume',
                'description' => 'Pilihan kostum tematik untuk menambah variasi sesi foto.',
                'icon' => 'CT',
            ],
            [
                'id' => 'extra_time',
                'name' => 'Extra Time',
                'price' => 20000,
                'unit' => '/7 menit',
                'description' => 'Penambahan waktu sesi foto selama 7 menit.',
                'icon' => 'TM',
            ],
            [
                'id' => 'keychain',
                'name' => 'Keychain',
                'price' => 15000,
                'unit' => '/2 pcs',
                'description' => 'Pasang fotomu di frame keychain. Produk keychain, foto tidak termasuk.',
                'icon' => 'KC',
            ],
        ];
    }

    /**
     * Validasi dan hitung add-on terpilih dari payload request.
     *
     * @param mixed $rawAddOns
     * @return Collection<int, array{name: string, qty: int, price: int, subtotal: int}>
     */
    private function resolveSelectedAddOns(mixed $rawAddOns): Collection
    {
        if (!is_array($rawAddOns)) {
            return collect();
        }

        $catalog = collect($this->getAddOnCatalog())->keyBy('id');

        return collect($rawAddOns)
            ->map(function ($value, $id) use ($catalog) {
                $addon = $catalog->get((string) $id);

                if (!$addon) {
                    return null;
                }

                $qty = (int) (is_array($value) ? ($value['qty'] ?? 0) : 0);

                if ($qty < 1) {
                    return null;
                }

                return [
                    'name' => $addon['name'],
                    'qty' => $qty,
                    'price' => (int) $addon['price'],
                    'subtotal' => $qty * (int) $addon['price'],
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * Estimasi info kapasitas peserta berdasarkan nama paket.
     */
    private function inferPeopleLabel(string $packageName): string
    {
        $name = strtolower($packageName);

        if (str_contains($name, 'couple')) {
            return '2 person(s)';
        }

        if (str_contains($name, 'group')) {
            return '3 - 15 person(s)';
        }

        if (str_contains($name, 'solo')) {
            return '1 person(s)';
        }

        return '1 - 5 person(s)';
    }

    /**
     * Ringkasan benefit paket untuk tampilan card.
     *
     * @return array<int, string>
     */
    private function packageBenefits(ServicePackage $servicePackage): array
    {
        $benefits = ['Free All Soft File'];

        if ($servicePackage->duration_minutes >= 15) {
            $benefits[] = 'Free 1 Print Photo';
        }

        if ($servicePackage->duration_minutes >= 45) {
            $benefits[] = 'Bonus 1 Background Setup';
        }

        return $benefits;
    }
}
