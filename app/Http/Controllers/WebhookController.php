<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();

        if ($payload['type'] !== 'payment') {
            return response()->json([
                'message' => 'Invalid payload type',
            ]);
        }

        return response()->json([
            'message' => 'Webhook received',
        ]);
    }
}
