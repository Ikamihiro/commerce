<?php

namespace App\Services\Webhook\Payloads;

use Illuminate\Http\Request;

class MercadoPagoPayload
{
    public function __construct(
        public readonly string $type,
        public readonly string $paymentId,
        public readonly array $data
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            type: $request->input('type'),
            paymentId: $request->input('data.id'),
            data: $request->input('data', []),
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'paymentId' => $this->paymentId,
            'data' => $this->data,
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
