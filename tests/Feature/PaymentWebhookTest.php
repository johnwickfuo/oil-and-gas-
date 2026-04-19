<?php

namespace Tests\Feature;

use App\Events\PaymentSucceeded;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('paystack.secretKey', 'sk_test_secret');
        config()->set('paystack.paymentUrl', 'https://api.paystack.co');
        config()->set('flutterwave.secretKey', 'flw_sk_test');
        config()->set('flutterwave.secretHash', 'flw-hash');
        config()->set('flutterwave.paymentUrl', 'https://api.flutterwave.com/v3');

        $service = Service::query()->create([
            'title' => 'Private Chef Dinner',
            'slug' => 'private-chef-dinner',
            'short_description' => 'x',
            'long_description' => '<p>x</p>',
            'base_price' => 250000,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->booking = Booking::create([
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'service_id' => $service->id,
            'event_date' => now()->addWeek()->toDateString(),
            'event_time' => '19:00',
            'guests' => 6,
            'location' => 'GRA',
            'addons' => [],
            'estimated_total' => 155000,
            'deposit_amount' => 46500,
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_paystack_webhook_rejects_invalid_signature(): void
    {
        $body = json_encode(['event' => 'charge.success', 'data' => ['reference' => 'NONE']]);

        $response = $this->call(
            'POST',
            '/webhooks/paystack',
            [], [], [],
            ['HTTP_X-Paystack-Signature' => 'bogus', 'CONTENT_TYPE' => 'application/json'],
            $body,
        );

        $response->assertStatus(401);
    }

    public function test_paystack_webhook_marks_payment_paid_and_is_idempotent(): void
    {
        Event::fake([PaymentSucceeded::class]);

        $payment = Payment::create([
            'booking_id' => $this->booking->id,
            'gateway' => 'paystack',
            'reference' => 'BD-WEBHOOK-REF',
            'amount' => 46500,
            'currency' => 'NGN',
            'status' => 'pending',
            'gateway_response' => [],
        ]);

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => 4650000,
                    'reference' => $payment->reference,
                ],
            ], 200),
        ]);

        $body = json_encode(['event' => 'charge.success', 'data' => ['reference' => $payment->reference]]);
        $signature = hash_hmac('sha512', $body, 'sk_test_secret');

        $first = $this->call(
            'POST',
            '/webhooks/paystack',
            [], [], [],
            ['HTTP_X-Paystack-Signature' => $signature, 'CONTENT_TYPE' => 'application/json'],
            $body,
        );
        $first->assertOk();

        $payment->refresh();
        $this->assertSame('success', $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame('confirmed', $this->booking->fresh()->status);
        Event::assertDispatchedTimes(PaymentSucceeded::class, 1);

        $second = $this->call(
            'POST',
            '/webhooks/paystack',
            [], [], [],
            ['HTTP_X-Paystack-Signature' => $signature, 'CONTENT_TYPE' => 'application/json'],
            $body,
        );
        $second->assertOk();
        Event::assertDispatchedTimes(PaymentSucceeded::class, 1);
    }

    public function test_flutterwave_webhook_accepts_matching_verif_hash(): void
    {
        Event::fake([PaymentSucceeded::class]);

        $payment = Payment::create([
            'booking_id' => $this->booking->id,
            'gateway' => 'flutterwave',
            'reference' => 'BD-FLW-REF',
            'amount' => 46500,
            'currency' => 'NGN',
            'status' => 'pending',
            'gateway_response' => [],
        ]);

        Http::fake([
            'api.flutterwave.com/v3/transactions/verify_by_reference*' => Http::response([
                'status' => 'success',
                'data' => [
                    'status' => 'successful',
                    'amount' => 46500,
                    'tx_ref' => $payment->reference,
                ],
            ], 200),
        ]);

        $body = json_encode(['event' => 'charge.completed', 'data' => ['tx_ref' => $payment->reference]]);

        $response = $this->call(
            'POST',
            '/webhooks/flutterwave',
            [], [], [],
            ['HTTP_VERIF-HASH' => 'flw-hash', 'CONTENT_TYPE' => 'application/json'],
            $body,
        );

        $response->assertOk();
        $payment->refresh();
        $this->assertSame('success', $payment->status);
        Event::assertDispatched(PaymentSucceeded::class);
    }

    public function test_webhook_routes_are_csrf_exempt(): void
    {
        $response = $this->call(
            'POST',
            '/webhooks/paystack',
            [], [], [],
            ['HTTP_X-Paystack-Signature' => 'bogus', 'CONTENT_TYPE' => 'application/json'],
            '{}',
        );

        $response->assertStatus(401);
        $this->assertNotSame(419, $response->getStatusCode(), 'Webhook route should be CSRF-exempt.');
    }
}
