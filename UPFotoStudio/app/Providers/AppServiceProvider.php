<?php

namespace App\Providers;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\WebsiteContent;
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
        // Abstraksi gateway pembayaran agar implementasi mudah diganti.
        $this->app->bind(PaymentGatewayInterface::class, MockQrisGateway::class);
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
