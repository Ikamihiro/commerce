<?php

namespace App\Services\Webhook\Processors;

use App\Exceptions\InvalidWebhookException;
use App\Exceptions\PaymentNotFoundException;
use App\Jobs\ProcessStudentEnrollment;
use App\Mail\OrderApproved;
use App\Models\Payment;
use App\Models\WebhookEntry;
use App\Services\Payment\MercadoPagoService;
use App\Services\Webhook\WebhookPayload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MercadoPagoWebhookProcessor implements WebhookProcessorContract
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService,
    ) {}

    public function process(WebhookPayload $payload): void
    {
        Log::info('Processing Mercado Pago webhook', [
            'payload' => $payload->toArray(),
        ]);

        if (!$this->isValidPayload($payload)) {
            throw new InvalidWebhookException('Invalid webhook payload');
        }

        WebhookEntry::create(['payload' => $payload->toArray()]);

        $payment = $this->fetchAndUpdatePayment($payload->paymentId);

        Log::info('Processing payment', [
            'payment' => $payment->toArray(),
        ]);

        match ($payment->status) {
            'authorized' => $this->handleAuthorizedPayment($payment),
            'approved' => $this->handleApprovedPayment($payment),
            'cancelled', 'refunded' => $this->handleCancelledPayment($payment),
            default => null
        };
    }

    private function isValidPayload(WebhookPayload $payload): bool
    {
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

        $payment->load([
            'order.student',
            'order.course',
        ]);

        return $payment;
    }

    private function handleAuthorizedPayment(Payment $payment): void
    {
        Log::info('Capturing payment', [
            'payment' => $payment->toArray(),
        ]);

        $paymentFromApi = $this->mercadoPagoService->capture(
            $payment->external_id,
            (float) $payment->amount,
        );

        $payment->update([
            'data' => [
                'id' => $paymentFromApi->id,
                'status' => $paymentFromApi->status,
                'status_detail' => $paymentFromApi->status_detail,
                'payment_method_id' => $paymentFromApi->payment_method_id,
                'payment_type_id' => $paymentFromApi->payment_type_id,
                'issuer_id' => $paymentFromApi->issuer_id,
                'installments' => $paymentFromApi->installments,
                'transaction_amount' => $paymentFromApi->transaction_amount,
                'description' => $paymentFromApi->description,
                'payer' => $paymentFromApi->payer,
                'type' => $payment->type,
            ],
            'status' => $paymentFromApi->status,
        ]);

        Log::info('Payment captured', [
            'payment' => $payment->toArray(),
        ]);
    }

    private function handleApprovedPayment(Payment $payment): void
    {
        $payment->order()->update([
            'status' => 'approved',
        ]);

        Log::info('Payment approved', [
            'payment' => $payment->toArray(),
        ]);

        ProcessStudentEnrollment::dispatch(
            $payment->order->student,
            $payment->order->course,
        );

        Mail::to($payment->order->student->email)
            ->send(new OrderApproved(
                $payment->order->course,
                $payment->order->student,
            ));

        Log::info('Student notified about enrollment', [
            'student' => $payment->order->student->toArray(),
            'course' => $payment->order->course->toArray(),
        ]);
    }

    private function handleCancelledPayment(Payment $payment): void
    {
        $payment->order()->update([
            'status' => 'cancelled',
        ]);

        Log::info('Payment cancelled', [
            'payment' => $payment->toArray(),
        ]);

        // ProcessStudentUnenrollment::dispatch(
        //     $payment->order->student,
        //     $payment->order->course,
        // );

        // Mail::to($payment->order->student->email)
        //     ->send(new OrderCancelled(
        //         $payment->order->course,
        //         $payment->order->student,
        //     ));

        // Log::info('Student notified about unenrollment', [
        //     'student' => $payment->order->student->toArray(),
        //     'course' => $payment->order->course->toArray(),
        // ]);
    }
}
