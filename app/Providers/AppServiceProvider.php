<?php

namespace App\Providers;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        // 1. Keep your existing product observer intact
        Product::observe(ProductObserver::class);

        // 2. Force HTTPS only when running on Render (production)
        if (config('app.env') === 'production') {
            $url->forceScheme('https');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}