<?php

namespace App\Services\Webhook\Processors;

use App\Services\Webhook\WebhookPayload;

interface WebhookProcessorContract
{
    public function process(WebhookPayload $payload): void;
}
