<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;

class WebhookPayload
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
            data: $request->all()
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
}
