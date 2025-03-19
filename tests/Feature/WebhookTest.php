<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function Pest\Laravel\postJson;

beforeEach(function () {
    // Defina uma chave secreta fictícia para os testes
    config()->set('services.mercadopago.webhook_secret', 'test_secret_key');
});

it('accepts a valid webhook signature', function () {
    // Simulação dos valores
    $dataID = '12345';
    $xRequestId = 'test-request-id';
    $ts = '1710790800'; // Um timestamp fictício

    // Criar a string de manifesto como no controller
    $manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";

    // Gerar o hash correto
    $validHash = hash_hmac('sha256', $manifest, 'test_secret_key');

    // Criar a assinatura válida
    $xSignature = "ts=$ts,v1=$validHash";

    // Enviar a requisição simulada
    postJson('/api/webhook', createWebhookPayload(), [
        'x-signature' => $xSignature,
        'x-request-id' => $xRequestId
    ])->assertStatus(200);
});

it('rejects a webhook with an invalid signature', function () {
    // Assinatura incorreta propositalmente
    $xSignature = "ts=1710790800,v1=invalid_hash";
    $xRequestId = 'test-request-id';

    postJson('/api/webhook', createWebhookPayload(), [
        'x-signature' => $xSignature,
        'x-request-id' => $xRequestId
    ])->assertStatus(400)->assertJson([
        'status' => 'error',
        'message' => 'Invalid webhook request'
    ]);
});

it('rejects a webhook with missing headers', function () {
    postJson('/api/webhook', createWebhookPayload())
        ->assertStatus(400)
        ->assertJson([
            'status' => 'error',
            'message' => 'Invalid webhook request'
        ]);
});

it('rejects a webhook with missing data.id in query params', function () {
    $xRequestId = 'test-request-id';
    $ts = '1710790800';
    $manifest = "id:;request-id:$xRequestId;ts:$ts;"; // `id` está vazio
    $validHash = hash_hmac('sha256', $manifest, 'test_secret_key');
    $xSignature = "ts=$ts,v1=$validHash";

    postJson('/api/webhook', createWebhookPayload(), [
        'x-signature' => $xSignature,
        'x-request-id' => $xRequestId
    ])->assertStatus(400)->assertJson([
        'status' => 'error',
        'message' => 'Invalid webhook request'
    ]);
});

function createWebhookPayload()
{
    return [
        'data' => [
            'id' => "12345",
        ],
        'type' => 'payment',
        "action" => "payment.created",
        "api_version" => "v1",
        "date_created" => "2025-03-19T20:14:53Z",
        "id" => 119954270532,
        "live_mode" => false,
        "type" => "payment",
        "user_id" => "268531771"
    ];
}
