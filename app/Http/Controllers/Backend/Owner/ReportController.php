<?php

namespace App\Http\Controllers\Backend\Owner;

use App\Exports\TransactionReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman laporan transaksi owner.
     */
    public function index(Request $request, ReportService $reportService): View
    {
        $filter = $this->validateFilter($request);
        $report = $reportService->getTransactionReport(
            $filter['period'],
            $filter['from'] ?? null,
            $filter['to'] ?? null
        );

        return view('backend.owner.reports.index', [
            'transactions' => $report['transactions'],
            'summary' => $report['summary'],
            'filter' => $filter,
        ]);
    }

    /**
     * Export PDF laporan owner.
     */
    public function exportPdf(Request $request, ReportService $reportService)
    {
        $filter = $this->validateFilter($request);
        $report = $reportService->getTransactionReport(
            $filter['period'],
            $filter['from'] ?? null,
            $filter['to'] ?? null
        );

        $pdf = Pdf::loadView('pdf.reports.transactions', [
            'title' => 'Laporan Transaksi Owner',
            'transactions' => $report['transactions'],
            'summary' => $report['summary'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-owner-' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * Export Excel laporan owner.
     */
    public function exportExcel(Request $request, ReportService $reportService)
    {
        $filter = $this->validateFilter($request);
        $report = $reportService->getTransactionReport(
            $filter['period'],
            $filter['from'] ?? null,
            $filter['to'] ?? null
        );

        $filename = 'laporan-owner-' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new TransactionReportExport($report['transactions']), $filename);
    }

    /**
     * Validasi filter periode laporan.
     *
     * @return array{period: string, from: ?string, to: ?string}
     */
    private function validateFilter(Request $request): array
    {
        /** @var array{period?: string, from?: ?string, to?: ?string} $validated */
        $validated = $request->validate([
            'period' => ['nullable', 'in:daily,weekly,monthly,yearly,custom'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $period = $validated['period'] ?? 'daily';

        if ($period === 'custom' && (empty($validated['from']) || empty($validated['to']))) {
            $period = 'daily';
        }

        return [
            'period' => $period,
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
        ];
    }
}
