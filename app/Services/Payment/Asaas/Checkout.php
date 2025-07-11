<?php

namespace App\Services\Payment\Asaas;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Checkout
{
    public function __construct(
        protected Course $course,
        protected Student $student,
    ) {}

    protected function getImageBase64(): ?string
    {
        if (!$this->course->cover) {
            return null;
        }

        $imagePath = Storage::path($this->course->cover);
        if (!file_exists($imagePath)) {
            return null;
        }

        return base64_encode(file_get_contents($imagePath));
    }

    protected function build(): array
    {
        return [
            'billingTypes' => ['PIX', 'CREDIT_CARD'],
            'minutesToExpire' => 60 * 2, // 2 hours
            'chargeTypes' => ['DETACHED', 'INSTALLMENT'],
            'installment' => [
                'maxInstallmentCount' => $this->course->max_installments,
            ],
            'customer' => $this->student->external_id ?: $this->student->id,
            'callback' => [
                // 'successUrl' => route('courses.checkout.success', ['course' => $this->course]),
                // 'cancelUrl' => route('courses.checkout.cancel', ['course' => $this->course]),
                // 'expiredUrl' => route('courses.checkout.expired', ['course' => $this->course]),
                'successUrl' => 'https://example.com/success',
                'cancelUrl' => 'https://example.com/cancel',
                'expiredUrl' => 'https://example.com/expired',
            ],
            'items' => [
                [
                    'name' => $this->course->name,
                    'description' => substr($this->course->description, 0, 150),
                    'quantity' => 1,
                    'value' => $this->course->price,
                    'imageBase64' => $this->getImageBase64(),
                ]
            ],
        ];
    }

    public function create(): array
    {
        /**
         * @var \Illuminate\Http\Client\Response $response
         */
        $response = Http::asaas()->post('checkouts', $this->build());

        if (!$response->successful()) {
            logger()->error('Asaas Checkout Error', [
                'response' => $response->json(),
                'payload' => $this->build(),
            ]);
            throw new \Exception("Failed to create checkout");
        }

        return [
            'url' => $response->json('link') ?: route('landing'),
            'id' => $response->json('id'),
            'status' => $response->json('status'),
        ];
    }
}
