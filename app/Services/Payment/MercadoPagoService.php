<?php

namespace App\Services\Payment;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoService
{
    protected PaymentClient $client;

    protected RequestOptions $requestOptions;

    public function __construct()
    {
        $this->client = new PaymentClient();
        $this->requestOptions = new RequestOptions();
        $this->requestOptions->setCustomHeaders([
            "X-Idempotency-Key: " . config('services.mercadopago.idempotency_key'),
        ]);
    }

    public function fetchById(string $paymentId)
    {
        return $this->client->get($paymentId, $this->requestOptions);
    }

    public function create(array $data)
    {
        return $this->client->create($data, $this->requestOptions);
    }

    public function capture(string $paymentId, float $amount)
    {
        return $this->client->capture($paymentId, $amount, $this->requestOptions);
    }
}
