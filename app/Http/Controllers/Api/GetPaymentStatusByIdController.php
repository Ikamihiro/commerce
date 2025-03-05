<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\MercadoPagoService;

class GetPaymentStatusByIdController extends Controller
{
    public function __invoke(Payment $payment, MercadoPagoService $mercadoPagoService)
    {
        $paymentFromApi = $mercadoPagoService->fetchById($payment->external_id);

        return response()->json([
            'status' => $paymentFromApi->status,
        ]);
    }
}
