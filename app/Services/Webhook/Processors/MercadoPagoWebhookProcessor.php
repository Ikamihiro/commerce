<?php

namespace App\Services\Webhook\Processors;

use App\Exceptions\InvalidWebhookException;
use App\Exceptions\PaymentNotFoundException;
use App\Jobs\ProcessStudentEnrollment;
use App\Jobs\ProcessStudentUnenrollment;
use App\Models\Payment;
use App\Models\WebhookEntry;
use App\Services\Payment\MercadoPagoService;
use App\Services\Webhook\WebhookPayload;

class MercadoPagoWebhookProcessor implements WebhookProcessorContract
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService,
    ) {}

    public function process(WebhookPayload $payload): void
    {
        if (!$this->isValidPayload($payload)) {
            throw new InvalidWebhookException('Invalid webhook payload');
        }

        WebhookEntry::create(['payload' => $payload->toArray()]);

        $payment = $this->fetchAndUpdatePayment($payload->paymentId);

        match ($payment->status) {
            'authorized' => $this->handleAuthorizedPayment($payment),
            'approved' => $this->handleApprovedPayment($payment),
            'cancelled', 'refunded' => $this->handleCancelledPayment($payment),
            default => null
        };
    }

    private function isValidPayload(WebhookPayload $payload): bool
    {
        // TODO: valid signature also
        return $payload->type === 'payment';
    }

    private function fetchAndUpdatePayment(string $paymentId): Payment
    {
        $paymentFromApi = $this->mercadoPagoService->fetchById($paymentId);

        /** @var Payment $payment */
        $payment = Payment::where('external_id', $paymentFromApi->id)->first();
        if (!$payment) {
            throw new PaymentNotFoundException('Payment not found');
        }

        $payment->update([
            'status' => $paymentFromApi->status,
        ]);

        return $payment;
    }

    private function handleAuthorizedPayment(Payment $payment): void
    {
        $this->mercadoPagoService->capture($payment->external_id, (float) $payment->amount);
    }

    private function handleApprovedPayment(Payment $payment): void
    {
        $payment->order()->update([
            'status' => 'approved',
        ]);

        ProcessStudentEnrollment::dispatch(
            $payment->order->student,
            $payment->order->course,
        );

        // TODO: notify client
    }

    private function handleCancelledPayment(Payment $payment): void
    {
        $payment->order()->update([
            'status' => 'cancelled',
        ]);

        ProcessStudentUnenrollment::dispatch(
            $payment->order->student,
            $payment->order->course,
        );

        // TODO: notify client
    }
}
