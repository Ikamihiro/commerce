<?php

namespace App\Services\Payment\Processors;

use App\Services\Payment\MercadoPagoService;
use Illuminate\Support\Facades\Log;
use MercadoPago\Exceptions\MPApiException;

class PixPaymentProcessor implements PaymentProcessorContract
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService
    ) {}

    public function process(array $data)
    {
        try {
            $response = $this->mercadoPagoService->create([
                'transaction_amount' => (float) $data['transaction_amount'],
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
            ]);

            return [
                'id' => $response->id,
                'status' => $response->status,
                'status_detail' => $response->status_detail,
                'transaction_details' => $response->transaction_details,
                'transaction_amount' => $response->transaction_amount,
                'description' => $response->description,
                'payer' => $response->payer,
                'point_of_interaction' => $response->point_of_interaction,
                'type' => 'pix',
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
