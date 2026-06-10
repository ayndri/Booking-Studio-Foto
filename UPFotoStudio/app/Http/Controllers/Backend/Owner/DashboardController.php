<?php

namespace App\Http\Controllers\Backend\Owner;

use App\Http\Controllers\Controller;
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

        return view('backend.owner.dashboard', [
            'dailySummary' => $daily['summary'],
            'weeklySummary' => $weekly['summary'],
            'monthlySummary' => $monthly['summary'],
        ]);
    }
}
