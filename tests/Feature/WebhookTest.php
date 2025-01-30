<?php

test('if type payment returns a successful response', function () {
    $response = $this->post('/webhook', mockWebhookPayload('payment'));

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Webhook received',
    ]);
});

test('if type not payment returns an empty response', function () {
    $response = $this->post('/webhook', mockWebhookPayload('not-payment'));

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Invalid payload type',
    ]);
});

function mockWebhookPayload($type): array
{
    return [
        "action" => "payment.updated",
        "api_version" => "v1",
        "data" => [
            "id" => "123456"
        ],
        "date_created" => "2021-11-01T02:02:02Z",
        "id" => "123456",
        "live_mode" => false,
        "type" => $type,
        "user_id" => 268531771
    ];
}
