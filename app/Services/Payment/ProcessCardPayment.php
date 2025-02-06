<?php

namespace App\Services\Payment;

use App\Services\Payment\ProcesserContract;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class ProcessCardPayment implements ProcesserContract
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
                "capture" => false,
                'transaction_amount' => (float) $data['transaction_amount'],
                'token' => $data['token'],
                'description' => $data['description'],
                'installments' => $data['installments'] ?? 1,
                'payment_method_id' => $data['payment_method_id'],
                'issuer_id' => $data['issuer_id'],
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
                'payment_method_id' => $response->payment_method_id,
                'payment_type_id' => $response->payment_type_id,
                'issuer_id' => $response->issuer_id,
                'installments' => $response->installments,
                'transaction_amount' => $response->transaction_amount,
                'description' => $response->description,
                'payer' => $response->payer,
                'type' => 'card',
            ];
        } catch (MPApiException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
