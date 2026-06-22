<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Auto-release booking yang pembayarannya kadaluarsa.
 *
 * Transaksi PENDING yang sudah melewati expires_at ditandai EXPIRED, dan
 * booking terkait di-CANCELLED sehingga slot jadwalnya kembali tersedia.
 */
class ReleaseExpiredBookings extends Command
{
    protected $signature = 'bookings:release-expired';

    protected $description = 'Tandai transaksi pembayaran yang kadaluarsa dan lepaskan slot booking-nya.';

    public function handle(): int
    {
        $expiredTransactions = PaymentTransaction::query()
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->whereHas('booking', fn ($query) => $query->where('status', Booking::STATUS_PENDING_PAYMENT))
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info('Tidak ada booking kadaluarsa.');

            return self::SUCCESS;
        }

        $released = 0;

        foreach ($expiredTransactions as $transaction) {
            DB::transaction(function () use ($transaction, &$released) {
                // Kunci ulang baris agar tidak balapan dengan webhook pembayaran.
                $locked = PaymentTransaction::query()
                    ->whereKey($transaction->id)
                    ->lockForUpdate()
                    ->first();

                if (!$locked || $locked->status !== PaymentTransaction::STATUS_PENDING) {
                    return;
                }

                $locked->update(['status' => PaymentTransaction::STATUS_EXPIRED]);

                $booking = $locked->booking()->lockForUpdate()->first();

                if ($booking && $booking->status === Booking::STATUS_PENDING_PAYMENT) {
                    $booking->update(['status' => Booking::STATUS_CANCELLED]);
                    $released++;
                }
            });
        }

        Log::info('Auto-release booking kadaluarsa.', ['released' => $released]);
        $this->info("Berhasil melepaskan {$released} booking kadaluarsa.");

        return self::SUCCESS;
    }
}
