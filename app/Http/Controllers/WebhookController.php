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
            $payload = WebhookPayload::fromRequest($request);

            $this->webhookProcessor->process($payload);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (InvalidWebhookException $e) {
            Log::error('Invalid webhook request', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        } catch (PaymentNotFoundException $e) {
            Log::error('Payment not found', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal server error',
            ], 500);
        }
    }
}
