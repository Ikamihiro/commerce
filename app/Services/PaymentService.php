<?php

namespace App\Services;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;

class PaymentService
{
    protected PaymentClient $client;

    public function __construct(PaymentClient $client)
    {
        $this->client = $client;
    }

    public function create(array $request)
    {
        $requestOptions = new RequestOptions();
        $requestOptions->setCustomHeaders(["X-Idempotency-Key: " . config('services.mercado_pago.idempotency_key')]);

        return $this->client->create($request, $requestOptions);
    }

    public function get(string $id)
    {
        return $this->client->get($id);
    }
}
