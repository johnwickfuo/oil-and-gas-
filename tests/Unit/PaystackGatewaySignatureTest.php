<?php

namespace Tests\Unit;

use App\Services\Payments\PaystackGateway;
use Tests\TestCase;

class PaystackGatewaySignatureTest extends TestCase
{
    public function test_valid_hmac_sha512_signature_is_accepted(): void
    {
        config()->set('paystack.secretKey', 'sk_test_secret');

        $body = '{"event":"charge.success","data":{"reference":"BD-20260501-ABCD-AA11"}}';
        $valid = hash_hmac('sha512', $body, 'sk_test_secret');

        $gateway = new PaystackGateway();

        $this->assertTrue($gateway->verifyWebhookSignature($body, $valid));
    }

    public function test_tampered_body_is_rejected(): void
    {
        config()->set('paystack.secretKey', 'sk_test_secret');

        $body = '{"event":"charge.success","data":{"reference":"BD-20260501-ABCD-AA11"}}';
        $valid = hash_hmac('sha512', $body, 'sk_test_secret');

        $gateway = new PaystackGateway();

        $this->assertFalse($gateway->verifyWebhookSignature($body.'tampered', $valid));
    }

    public function test_wrong_secret_is_rejected(): void
    {
        config()->set('paystack.secretKey', 'sk_test_secret');
        $body = '{"event":"charge.success"}';
        $wrong = hash_hmac('sha512', $body, 'sk_test_other');

        $gateway = new PaystackGateway();

        $this->assertFalse($gateway->verifyWebhookSignature($body, $wrong));
    }

    public function test_empty_signature_is_rejected(): void
    {
        config()->set('paystack.secretKey', 'sk_test_secret');

        $gateway = new PaystackGateway();

        $this->assertFalse($gateway->verifyWebhookSignature('{"any":"body"}', ''));
    }

    public function test_missing_secret_rejects_signature(): void
    {
        config()->set('paystack.secretKey', '');

        $gateway = new PaystackGateway();

        $this->assertFalse($gateway->verifyWebhookSignature('{}', 'anything'));
    }
}
