<?php

namespace App\Providers;

use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentService::class, function () {
            return new PaymentService(
                new \MercadoPago\Client\Payment\PaymentClient(),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        MercadoPagoConfig::setAccessToken(config('services.mercado_pago.access_token'));

        if (config('services.mercado_pago.runtime_mode') !== 'server') {
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        }
    }
}
