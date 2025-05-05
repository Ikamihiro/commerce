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
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            if (!$this->isValidPayload($request)) {
                throw new InvalidWebhookException('HMAC verification failed');
            }

            $payload = WebhookPayload::fromRequest($request);

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

    private function isValidPayload(Request $request): bool
    {
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');

        if (!$xSignature || !$xRequestId) {
            Log::warning('Webhook request missing signature or request ID');
            return false;
        }

        $dataID = $request->has('data.id') ? $request->input('data.id') : '';

        if (!$dataID) {
            Log::warning('Webhook request missing data ID');
            return false;
        }

        $parts = explode(',', $xSignature);
        $ts = null;
        $hash = null;

        foreach ($parts as $part) {
            $keyValue = explode('=', $part, 2);
            if (count($keyValue) == 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);
                if ($key === "ts") {
                    $ts = $value;
                } elseif ($key === "v1") {
                    $hash = $value;
                }
            }
        }

        if (!$ts || !$hash) {
            Log::warning('Invalid webhook signature format');
            return false;
        }

        $secret = config('services.mercado_pago.webhook_secret');
        $manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";
        $generatedHash = hash_hmac('sha256', $manifest, $secret);

        if (!hash_equals($generatedHash, $hash)) {
            Log::error('HMAC verification failed', [
                'expected' => $generatedHash,
                'received' => $hash
            ]);
            return false;
        }

        return true;
    }
}
