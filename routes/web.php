<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Backend\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Backend\Admin\ContentController;
use App\Http\Controllers\Backend\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Backend\Admin\ServicePackageController;
use App\Http\Controllers\Backend\Admin\StudioController;
use App\Http\Controllers\Backend\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Backend\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Backend\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\ContactMessageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentRedirectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website Routes
|--------------------------------------------------------------------------
| Frontend publik untuk guest tanpa login.
*/
Route::get('/', [HomeController::class, 'home'])->name('frontend.home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('frontend.about');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('frontend.gallery');
Route::get('/paket-harga', [HomeController::class, 'pricing'])->name('frontend.pricing');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('frontend.terms');
Route::get('/kontak', [HomeController::class, 'contact'])->name('frontend.contact');
Route::get('/coming-soon', [HomeController::class, 'comingSoon'])->name('frontend.coming-soon');
Route::post('/kontak', [ContactMessageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('frontend.contact.store');

Route::get('/booking', [BookingController::class, 'create'])->name('frontend.booking.create');
Route::get('/booking/paket/{servicePackage}', [BookingController::class, 'packageDetail'])->name('frontend.booking.package-detail');
Route::get('/booking/order', [BookingController::class, 'order'])->name('frontend.booking.order');
Route::post('/booking', [BookingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('frontend.booking.store');
Route::get('/booking/status/{invoiceNumber}', [BookingController::class, 'status'])->name('frontend.booking.status');
Route::get('/booking/invoice/{invoiceNumber}', [BookingController::class, 'invoice'])->name('frontend.booking.invoice');
Route::get('/booking/studios/{studio}/packages', [BookingController::class, 'packagesByStudio'])
    ->name('frontend.booking.packages-by-studio');
Route::get('/booking/paket/{servicePackage}/slots', [BookingController::class, 'slotsJson'])
    ->name('frontend.booking.slots');

/*
| Redirect URL setelah pembayaran Midtrans (dipasang di dashboard Midtrans).
*/
Route::get('/payment/finish', [PaymentRedirectController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentRedirectController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentRedirectController::class, 'error'])->name('payment.error');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Login gabungan untuk dashboard admin/owner.
*/
Route::middleware('guest:admin,owner')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin,owner')->group(function () {
        Route::get('/login', fn () => redirect()->route('login'))->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::post('/logout', [AuthController::class, 'logoutAdmin'])
        ->middleware('auth:admin')
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('studios', StudioController::class)->except(['show']);
        Route::resource('service-packages', ServicePackageController::class)->except(['show']);
        Route::resource('contents', ContentController::class)->except(['show']);

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::patch('/contact-messages/{contactMessage}/read', [AdminContactMessageController::class, 'markRead'])
            ->name('contact-messages.mark-read');

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });
});

/*
|--------------------------------------------------------------------------
| Owner Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->name('owner.')->group(function () {
    Route::middleware('guest:admin,owner')->group(function () {
        Route::get('/login', fn () => redirect()->route('login'))->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::post('/logout', [AuthController::class, 'logoutOwner'])
        ->middleware('auth:owner')
        ->name('logout');

    Route::middleware(['auth:owner', 'role:owner'])->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [OwnerReportController::class, 'exportPdf'])->name('reports.export-pdf');
        Route::get('/reports/export/excel', [OwnerReportController::class, 'exportExcel'])->name('reports.export-excel');
    });
});
