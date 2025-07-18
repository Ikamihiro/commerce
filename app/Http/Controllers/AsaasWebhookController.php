<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidWebhookException;
use App\Exceptions\PaymentNotFoundException;
use App\Services\Webhook\Payloads\AsaasPayload;
use App\Services\Webhook\Processors\AsaasWebhookProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    public function __construct(
        protected AsaasWebhookProcessor $webhookProcessor
    ) {}

    public function __invoke(Request $request)
    {
        try {
            Log::info('Webhook request received', [
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            if (!$this->validateWebhook($request)) {
                throw new InvalidWebhookException('Webhook validation failed');
            }

            $payload = AsaasPayload::fromRequest($request);

            $this->webhookProcessor->process($payload);
        } catch (InvalidWebhookException $e) {
            Log::error('Invalid webhook request', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook request',
            ], 400);
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

    /**
     * Validates the webhook request using a token.
     *
     * @param Request $request
     * @return bool
     */
    private function validateWebhook(Request $request)
    {
        $webhookToken = config('services.asaas.webhook_token');
        $receivedToken = $request->header('Asaas-Access-Token');

        return hash_equals($webhookToken, $receivedToken);
    }
}
