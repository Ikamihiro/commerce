<?php

namespace App\Providers;

use App\Services\Payment\MercadoPagoService;
use App\Services\Webhook\Processors\MercadoPagoWebhookProcessor;
use App\Services\Webhook\Processors\WebhookProcessorContract;
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
        $this->app->bind(WebhookProcessorContract::class, MercadoPagoWebhookProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
