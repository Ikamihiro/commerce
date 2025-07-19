<?php

namespace App\Services\Webhook\Processors;

use App\Events\PaymentConfirmed;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WebhookEntry;
use App\Services\Payment\MercadoPagoService;
use App\Services\Webhook\Payloads\AsaasPayload;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasWebhookProcessor
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService,
    ) {}

    public function process(AsaasPayload $payload): void
    {
        Log::info('Processing Asaas webhook', [
            'event' => $payload->event,
            'data' => $payload->toArray(),
        ]);

        WebhookEntry::create([
            'payload' => $payload->toArray(),
            'provider' => 'asaas',
        ]);

        match ($payload->event) {
            'PAYMENT_CREATED' => $this->handlePaymentCreatedEvent($payload),
            'PAYMENT_CONFIRMED' => $this->handlePaymentConfirmedEvent($payload),
            'PAYMENT_CREDIT_CARD_CAPTURE_REFUSED', 'PAYMENT_OVERDUE' => $this->handlePaymentFailedEvent($payload),
            'CHECKOUT_CANCELLED' => $this->handleCheckoutCancelledEvent($payload),
            'CHECKOUT_EXPIRED' => $this->handleCheckoutExpiredEvent($payload),
            default => $this->handleUnknownEvent($payload),
        };
    }

    private function handlePaymentCreatedEvent(AsaasPayload $payload): void
    {
        $checkoutSession = $payload->getCheckoutSession();

        Log::info('Processing PAYMENT_CREATED event', [
            'checkout_session' => $checkoutSession,
        ]);

        /**
         * @var Order $order
         */
        $order = Order::ofCheckoutId($checkoutSession)
            ->with(['course', 'student'])
            ->firstOrFail();

        $payment = $order->payments()->where('external_id', $payload->paymentId)->first();
        if ($payment) {
            // If payment already exists, we can skip creating a new one
            // This might happen if the webhook is retried or if the payment was already created
            return;
        }

        $payment = $order->payments()->create([
            'external_id' => $payload->data['id'],
            'amount' => $payload->data['value'],
            'installments' => $payload->data['installmentNumber'] ?? 1,
            'data' => $payload->data,
            'type' => $payload->data['billingType'],
        ]);

        Log::info('Payment created for order', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
        ]);
    }

    private function handlePaymentConfirmedEvent(AsaasPayload $payload): void
    {
        $checkoutSession = $payload->getCheckoutSession();

        Log::info('Processing PAYMENT_CONFIRMED event', [
            'checkout_session' => $checkoutSession,
        ]);

        /**
         * @var Order $order
         */
        $order = Order::ofCheckoutId($checkoutSession)
            ->with(['course', 'student'])
            ->firstOrFail();

        $payment = $order->payments()->where('external_id', $payload->paymentId)->first();
        if (!$payment) {
            $payment = $order->payments()->create([
                'external_id' => $payload->data['id'],
                'amount' => $payload->data['value'],
                'installments' => $payload->data['installmentNumber'] ?? 1,
                'data' => $payload->data,
                'type' => $payload->data['billingType'],
                'status' => 'confirmed',
            ]);
        } else {
            $payment->update([
                'status' => 'confirmed',
                'data' => $payload->data,
            ]);
        }

        Log::info('Payment confirmed for order', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
        ]);

        PaymentConfirmed::dispatch($order, $payment);
    }

    private function handlePaymentFailedEvent(AsaasPayload $payload): void
    {
        $checkoutSession = $payload->getCheckoutSession();

        Log::info('Processing PAYMENT_FAILED event', [
            'checkout_session' => $checkoutSession,
        ]);

        /**
         * @var Order $order
         */
        $order = Order::ofCheckoutId($checkoutSession)
            ->with(['course', 'student'])
            ->firstOrFail();

        $payment = $order->payments()->where('external_id', $payload->paymentId)->first();
        if (!$payment) {
            // If payment does not exist, we cannot process the failed event
            throw new \Exception('Payment not found for failed event');
        }

        $payment->update([
            'status' => 'failed',
            'data' => $payload->data,
        ]);

        $order->update([
            'status' => 'payment_failed',
        ]);

        Log::info('Payment failed for order', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
        ]);

        // TODO: - Avisar o administrador sobre o pagamento falhou via email
    }

    private function handleUnknownEvent(AsaasPayload $payload): void
    {
        // Log or handle unknown events
        Log::warning('Unknown Asaas webhook event', [
            'event' => $payload->event,
            'data' => $payload->toArray(),
        ]);

        // Optionally, you can throw an exception or notify the admin
        // throw new \Exception('Unknown Asaas webhook event: ' . $payload->event);
    }

    private function handleCheckoutCancelledEvent(AsaasPayload $payload): void
    {
        $checkoutSession = $payload->getCheckoutSession();

        Log::info('Processing CHECKOUT_CANCELLED event', [
            'data' => $payload->toArray(),
        ]);

        /**
         * @var Order $order
         */
        $order = Order::ofCheckoutId($checkoutSession)
            ->with(['course', 'student'])
            ->firstOrFail();

        $order->update([
            'status' => 'cancelled',
        ]);
    }

    private function handleCheckoutExpiredEvent(AsaasPayload $payload): void
    {
        $checkoutSession = $payload->getCheckoutSession();

        Log::info('Processing CHECKOUT_EXPIRED event', [
            'data' => $payload->toArray(),
        ]);

        /**
         * @var Order $order
         */
        $order = Order::ofCheckoutId($checkoutSession)
            ->with(['course', 'student'])
            ->firstOrFail();

        $order->update([
            'status' => 'expired',
        ]);
    }
}
