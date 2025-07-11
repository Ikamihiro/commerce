<?php

namespace App\Providers;

use App\Services\Payment\MercadoPagoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MercadoPagoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Http::macro('asaas', function () {
            return Http::baseUrl(config('services.asaas.url'))
                ->acceptJson()
                ->contentType('application/json')
                ->withHeaders([
                    'User-Agent' => config('app.name', 'Laravel'),
                    'access_token' => config('services.asaas.access_token'),
                ]);
        });
    }
}
