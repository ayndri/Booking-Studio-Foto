<?php

namespace App\Exports;

use App\Models\PaymentTransaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @param Collection<int, PaymentTransaction> $transactions
     */
    public function __construct(private readonly Collection $transactions)
    {
    }

    /**
     * @return Collection<int, PaymentTransaction>
     */
    public function collection(): Collection
    {
        return $this->transactions;
    }

    /**
     * Header tabel laporan.
     *
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal',
            'Booking Code',
            'Studio',
            'Guest',
            'Payment Type',
            'Metode',
            'Nominal',
            'Status',
            'Waktu Bayar',
        ];
    }

    /**
     * Mapping field transaksi ke kolom export.
     *
     * @param PaymentTransaction $transaction
     * @return array<int, string|int|null>
     */
    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            optional($transaction->created_at)->format('Y-m-d H:i:s'),
            optional($transaction->booking)->booking_code,
            optional(optional($transaction->booking)->studio)->name,
            optional(optional($transaction->booking)->guest)->full_name,
            $transaction->payment_type,
            $transaction->payment_method,
            $transaction->amount,
            $transaction->status,
            optional($transaction->paid_at)->format('Y-m-d H:i:s'),
        ];
    }
}
