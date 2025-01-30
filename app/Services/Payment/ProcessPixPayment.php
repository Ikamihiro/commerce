<?php

namespace App\Services\Payment;

use App\Services\Payment\ProcesserContract;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

use function Illuminate\Log\log;

class ProcessPixPayment implements ProcesserContract
{
    public function process(array $data)
    {
        try {
            $client = new PaymentClient();
            $requestOptions = new RequestOptions();
            $requestOptions->setCustomHeaders([
                "X-Idempotency-Key: " . config('services.mercadopago.idempotency_key'),
            ]);

            $response = $client->create([
                'transaction_amount' => (float) $data['amount'],
                'description' => $data['description'],
                'payment_method_id' => $data['payment_method_id'],
                'payer' => [
                    'first_name' => $data['payer']['first_name'],
                    'last_name' => $data['payer']['last_name'],
                    'email' => $data['payer']['email'],
                    'identification' => [
                        'type' => $data['payer']['identification']['type'],
                        'number' => $data['payer']['identification']['number'],
                    ],
                ],
            ], $requestOptions);

            return [
                'id' => $response->id,
                'status' => $response->status,
                'status_detail' => $response->status_detail,
                'transaction_details' => $response->transaction_details,
                'transaction_amount' => $response->transaction_amount,
                'description' => $response->description,
                'payer' => $response->payer,
                'point_of_interaction' => $response->point_of_interaction,
            ];
        } catch (MPApiException $e) {
            dd($e);
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
