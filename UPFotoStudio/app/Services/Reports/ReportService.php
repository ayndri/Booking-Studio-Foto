<?php

namespace App\Services\Reports;

use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    /**
     * Ambil transaksi berdasarkan periode laporan.
     *
     * @return array{transactions: Collection<int, PaymentTransaction>, summary: array<string, mixed>}
     */
    public function getTransactionReport(string $period, ?string $from = null, ?string $to = null): array
    {
        [$startDate, $endDate] = $this->resolveDateRange($period, $from, $to);

        $transactions = PaymentTransaction::query()
            ->with(['booking.guest', 'booking.studio', 'booking.servicePackage'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_transactions' => $transactions->count(),
            'success_transactions' => $transactions->where('status', PaymentTransaction::STATUS_SUCCESS)->count(),
            'failed_transactions' => $transactions->where('status', PaymentTransaction::STATUS_FAILED)->count(),
            'expired_transactions' => $transactions->where('status', PaymentTransaction::STATUS_EXPIRED)->count(),
            'pending_transactions' => $transactions->where('status', PaymentTransaction::STATUS_PENDING)->count(),
            'total_success_amount' => (int) $transactions
                ->where('status', PaymentTransaction::STATUS_SUCCESS)
                ->sum('amount'),
            'total_amount' => (int) $transactions->sum('amount'),
        ];

        return [
            'transactions' => $transactions,
            'summary' => $summary,
        ];
    }

    /**
     * Tentukan rentang tanggal berdasarkan mode laporan.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveDateRange(string $period, ?string $from, ?string $to): array
    {
        $now = now();

        return match ($period) {
            'daily' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'monthly' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'yearly' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'custom' => [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
        };
    }
}
