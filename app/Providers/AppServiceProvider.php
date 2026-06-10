<?php

namespace App\Providers;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\WebsiteContent;
use App\Services\Payments\MidtransSnapGateway;
use App\Services\Payments\MockQrisGateway;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Gateway Midtrans di-resolve dari config (dipakai juga oleh finish redirect).
        $this->app->singleton(MidtransSnapGateway::class, function () {
            return new MidtransSnapGateway(
                serverKey: (string) config('services.midtrans.server_key'),
                isProduction: (bool) config('services.midtrans.is_production'),
                expiryMinutes: (int) config('services.midtrans.expiry_minutes', 30),
            );
        });

        // Abstraksi gateway pembayaran agar implementasi mudah diganti via PAYMENT_GATEWAY.
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            return config('services.payment_gateway') === 'midtrans'
                ? $app->make(MidtransSnapGateway::class)
                : $app->make(MockQrisGateway::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sesuaikan paginator dengan komponen Bootstrap 5.
        Paginator::useBootstrapFive();

        View::composer('layouts.frontend', function ($view) {
            $footer = [
                'brand' => 'UPSTUDIO',
                'description' => 'Aplikasi terbaik layanan booking studio di seluruh studio di Indonesia.',
                'contact' => 'Surabaya, Indonesia|hello@upfotostudio.test|(+62) 812 0000 0000',
                'copyright' => 'Copyright UPStudio',
            ];

            try {
                $contents = WebsiteContent::query()
                    ->whereIn('key', ['footer_brand', 'footer_description', 'footer_contact', 'footer_copyright'])
                    ->get()
                    ->keyBy('key');

                $footer['brand'] = trim((string) ($contents->get('footer_brand')?->title ?? $footer['brand']));
                $footer['description'] = trim((string) ($contents->get('footer_description')?->content ?? $footer['description']));
                $footer['contact'] = trim((string) ($contents->get('footer_contact')?->content ?? $footer['contact']));
                $footer['copyright'] = trim((string) ($contents->get('footer_copyright')?->content ?? $footer['copyright']));
            } catch (\Throwable) {
                // Abaikan error DB saat fase awal migrate/install.
            }

            $view->with('footerContent', $footer);
        });
    }
}
