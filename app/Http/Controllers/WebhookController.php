<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidWebhookException;
use App\Exceptions\PaymentNotFoundException;
use App\Services\Webhook\WebhookPayload;
use App\Services\Webhook\Processors\WebhookProcessorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private WebhookProcessorContract $webhookProcessor
    ) {}

    public function __invoke(Request $request)
    {
        try {
            Log::info('Webhook request received', [
                'payload' => $request->all()
            ]);

            $payload = WebhookPayload::fromRequest($request);

            $this->webhookProcessor->process($payload);
        } catch (InvalidWebhookException $e) {
            Log::error('Invalid webhook request', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'stack' => $e->getTraceAsString()
            ]);
        } catch (PaymentNotFoundException $e) {
            Log::error('Payment not found', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'stack' => $e->getTraceAsString()
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'stack' => $e->getTraceAsString()
            ]);
        }

        // Always return a success response to the webhook provider
        return response()->json([
            'status' => 'success',
        ]);
    }
}
