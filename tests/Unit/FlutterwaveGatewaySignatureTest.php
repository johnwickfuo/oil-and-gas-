<?php

namespace Tests\Unit;

use App\Services\Payments\FlutterwaveGateway;
use Tests\TestCase;

class FlutterwaveGatewaySignatureTest extends TestCase
{
    public function test_matching_verif_hash_is_accepted(): void
    {
        config()->set('flutterwave.secretHash', 'my-shared-hash');

        $gateway = new FlutterwaveGateway();

        $this->assertTrue($gateway->verifyWebhookSignature('{"event":"charge.completed"}', 'my-shared-hash'));
    }

    public function test_mismatched_verif_hash_is_rejected(): void
    {
        config()->set('flutterwave.secretHash', 'my-shared-hash');

        $gateway = new FlutterwaveGateway();

        $this->assertFalse($gateway->verifyWebhookSignature('{"event":"charge.completed"}', 'wrong-hash'));
    }

    public function test_empty_header_is_rejected(): void
    {
        config()->set('flutterwave.secretHash', 'my-shared-hash');

        $gateway = new FlutterwaveGateway();

        $this->assertFalse($gateway->verifyWebhookSignature('{}', ''));
    }

    public function test_missing_configured_hash_rejects_header(): void
    {
        config()->set('flutterwave.secretHash', '');

        $gateway = new FlutterwaveGateway();

        $this->assertFalse($gateway->verifyWebhookSignature('{}', 'any-hash'));
    }
}
