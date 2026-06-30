<?php

namespace App\Http\Controllers\Backend\Owner;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Services\Reports\ReportService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard ringkas khusus owner.
     */
    public function index(ReportService $reportService): View
    {
        $daily = $reportService->getTransactionReport('daily');
        $weekly = $reportService->getTransactionReport('weekly');
        $monthly = $reportService->getTransactionReport('monthly');

        // Pendapatan per bulan (6 bulan terakhir) dari transaksi sukses.
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->copy()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('M Y');
            $monthlyData[] = (int) PaymentTransaction::where('status', PaymentTransaction::STATUS_SUCCESS)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        // Komposisi pendapatan per studio (transaksi sukses).
        $revenueByStudio = PaymentTransaction::where('status', PaymentTransaction::STATUS_SUCCESS)
            ->with('booking.studio:id,name')
            ->get(['id', 'booking_id', 'amount'])
            ->groupBy(fn ($transaction) => $transaction->booking?->studio?->name ?? 'Lainnya')
            ->map(fn ($group) => (int) $group->sum('amount'))
            ->sortDesc();

        return view('backend.owner.dashboard', [
            'dailySummary' => $daily['summary'],
            'weeklySummary' => $weekly['summary'],
            'monthlySummary' => $monthly['summary'],
            'monthlyLabels' => $monthlyLabels,
            'monthlyData' => $monthlyData,
            'studioRevenueLabels' => $revenueByStudio->keys()->all(),
            'studioRevenueData' => $revenueByStudio->values()->all(),
        ]);
    }
}
