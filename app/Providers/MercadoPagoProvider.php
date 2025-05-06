<?php

namespace App\Providers;

use App\Services\MercadoPagoService;
use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->bind(MercadoPagoService::class, function () {
        //     return new MercadoPagoService(
        //         new \MercadoPago\Client\Payment\PaymentClient(),
        //     );
        // });
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
