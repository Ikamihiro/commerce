<?php

namespace App\Services\Payment;

interface ProcesserContract
{
    public function process(array $data);
}
