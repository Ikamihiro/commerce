<?php

namespace App\Services\Webhook\Payloads;

class AsaasPayload
{
    public function __construct(
        public readonly string $id,
        public readonly string $event,
        public readonly string $paymentId,
        public readonly array $data
    ) {}

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            id: $request->input('id'),
            event: $request->input('event'),
            paymentId: $request->input('payment.id'),
            data: $request->input('payment', []),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'paymentId' => $this->paymentId,
            'data' => $this->data,
        ];
    }

    public function getCheckoutSession(): string
    {
        $checkoutSession = $this->data['checkoutSession'] ?? null;
        if (!$checkoutSession) {
            throw new \Exception('Checkout session not found in payment data');
        }

        return $checkoutSession;
    }
}
