<?php

namespace App\Services\Payment\Processors;

interface PaymentProcessorContract
{
    public function process(array $data);
}
