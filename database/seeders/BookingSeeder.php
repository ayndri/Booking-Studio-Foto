<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\PaymentTransaction;
use App\Models\ServicePackage;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Seed contoh booking dan transaksi.
     */
    public function run(): void
    {
        $studio = Studio::query()->first();

        if (!$studio) {
            return;
        }

        $servicePackage = ServicePackage::query()->where('studio_id', $studio->id)->first();

        if (!$servicePackage) {
            return;
        }

        $guestOne = Guest::updateOrCreate(
            [
                'email' => 'guest1@example.com',
                'phone' => '081200000001',
            ],
            [
                'full_name' => 'Guest Contoh Satu',
            ]
        );

        $pastStart = Carbon::now()->subDay()->setTime(10, 0);
        $pastEnd = $pastStart->copy()->addMinutes($servicePackage->duration_minutes);

        $bookingOne = Booking::updateOrCreate(
            ['booking_code' => 'BOOK-SAMPLE-0001'],
            [
                'guest_id' => $guestOne->id,
                'studio_id' => $studio->id,
                'service_package_id' => $servicePackage->id,
                'booking_date' => $pastStart->toDateString(),
                'start_time' => $pastStart->format('H:i:s'),
                'end_time' => $pastEnd->format('H:i:s'),
                'add_on_amount' => 0,
                'total_amount' => $servicePackage->price,
                'payment_type' => Booking::PAYMENT_LUNAS,
                'status' => Booking::STATUS_CONFIRMED,
                'notes' => 'Seed data booking berhasil dibayar.',
            ]
        );

        PaymentTransaction::updateOrCreate(
            ['invoice_number' => 'INV-SAMPLE-0001'],
            [
                'booking_id' => $bookingOne->id,
                'payment_type' => Booking::PAYMENT_LUNAS,
                'payment_method' => 'QRIS',
                'amount' => $bookingOne->total_amount,
                'status' => PaymentTransaction::STATUS_SUCCESS,
                'gateway_reference' => 'MOCK-QRIS-SAMPLE1',
                'qr_payload' => 'MOCKQRIS|INVOICE:INV-SAMPLE-0001',
                'paid_at' => now()->subDay(),
                'expires_at' => now(),
                'callback_payload' => ['status' => 'SUCCESS', 'invoice_number' => 'INV-SAMPLE-0001'],
            ]
        );

        $guestTwo = Guest::updateOrCreate(
            [
                'email' => 'guest2@example.com',
                'phone' => '081200000002',
            ],
            [
                'full_name' => 'Guest Contoh Dua',
            ]
        );

        $futureStart = Carbon::now()->addDay()->setTime(14, 0);
        $futureEnd = $futureStart->copy()->addMinutes($servicePackage->duration_minutes);

        $bookingTwo = Booking::updateOrCreate(
            ['booking_code' => 'BOOK-SAMPLE-0002'],
            [
                'guest_id' => $guestTwo->id,
                'studio_id' => $studio->id,
                'service_package_id' => $servicePackage->id,
                'booking_date' => $futureStart->toDateString(),
                'start_time' => $futureStart->format('H:i:s'),
                'end_time' => $futureEnd->format('H:i:s'),
                'add_on_amount' => 50000,
                'total_amount' => $servicePackage->price + 50000,
                'payment_type' => Booking::PAYMENT_DP,
                'status' => Booking::STATUS_PENDING_PAYMENT,
                'notes' => 'Seed data booking menunggu pembayaran.',
            ]
        );

        PaymentTransaction::updateOrCreate(
            ['invoice_number' => 'INV-SAMPLE-0002'],
            [
                'booking_id' => $bookingTwo->id,
                'payment_type' => Booking::PAYMENT_DP,
                'payment_method' => 'QRIS',
                'amount' => max((int) round($bookingTwo->total_amount * 0.3), 50000),
                'status' => PaymentTransaction::STATUS_PENDING,
                'gateway_reference' => 'MOCK-QRIS-SAMPLE2',
                'qr_payload' => 'MOCKQRIS|INVOICE:INV-SAMPLE-0002',
                'expires_at' => now()->addMinutes(30),
            ]
        );
    }
}
