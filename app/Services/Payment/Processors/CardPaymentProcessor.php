<?php

namespace App\Services\Payment\Processors;

use App\Services\Payment\MercadoPagoService;
use Illuminate\Support\Facades\Log;
use MercadoPago\Exceptions\MPApiException;

class CardPaymentProcessor implements PaymentProcessorContract
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService
    ) {}

    public function process(array $data)
    {
        try {
            $response = $this->mercadoPagoService->create([
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
            ]);

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
            Log::error([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'stack' => $e->getTraceAsString(),
            ]);
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
