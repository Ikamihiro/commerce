<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function findByExternalId(string $externalId): ?Payment
    {
        return Payment::where('external_id', $externalId)->first();
    }

    public function findByExternalIdAndUpdate(string $externalId, array $data): ?Payment
    {
        $payment = $this->findByExternalId($externalId);

        if (!$payment) {
            return null;
        }

        $payment->update($data);

        return $payment;
    }
}
