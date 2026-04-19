<?php

namespace App\Services\Payments;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaystackGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'paystack';
    }

    public function initialize(Booking $booking): array
    {
        $secret = (string) config('paystack.secretKey');
        if ($secret === '') {
            throw new RuntimeException('Paystack secret key not configured.');
        }

        $payload = [
            'email' => $booking->email,
            'amount' => (int) round(((float) $booking->deposit_amount) * 100),
            'currency' => 'NGN',
            'reference' => $booking->reference . '-' . strtoupper(bin2hex(random_bytes(3))),
            'callback_url' => route('payment.callback', ['gateway' => 'paystack']),
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->reference,
                'customer_name' => $booking->name,
                'customer_phone' => $booking->phone,
            ],
        ];

        $response = Http::withToken($secret)
            ->acceptJson()
            ->asJson()
            ->post(config('paystack.paymentUrl') . '/transaction/initialize', $payload);

        Log::info('paystack.initialize', [
            'booking' => $booking->reference,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->successful() || ! ($response->json('status') === true)) {
            throw new RuntimeException('Failed to initialize Paystack transaction: ' . $response->body());
        }

        $data = $response->json('data');

        return [
            'authorization_url' => (string) $data['authorization_url'],
            'reference' => (string) $data['reference'],
            'raw' => $response->json() ?? [],
        ];
    }

    public function verify(string $reference): array
    {
        $secret = (string) config('paystack.secretKey');
        if ($secret === '') {
            throw new RuntimeException('Paystack secret key not configured.');
        }

        $response = Http::withToken($secret)
            ->acceptJson()
            ->get(config('paystack.paymentUrl') . '/transaction/verify/' . urlencode($reference));

        Log::info('paystack.verify', [
            'reference' => $reference,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->successful() || ! ($response->json('status') === true)) {
            return [
                'status' => 'failed',
                'amount' => 0.0,
                'reference' => $reference,
                'raw' => $response->json() ?? [],
            ];
        }

        $data = $response->json('data');
        $gatewayStatus = strtolower((string) ($data['status'] ?? 'failed'));

        return [
            'status' => $gatewayStatus === 'success' ? 'success' : ($gatewayStatus === 'abandoned' ? 'pending' : 'failed'),
            'amount' => ((int) ($data['amount'] ?? 0)) / 100,
            'reference' => (string) ($data['reference'] ?? $reference),
            'raw' => $response->json() ?? [],
        ];
    }

    public function verifyWebhookSignature(string $rawBody, string $signatureHeader): bool
    {
        $secret = (string) config('paystack.secretKey');
        if ($secret === '' || $signatureHeader === '') {
            return false;
        }

        $computed = hash_hmac('sha512', $rawBody, $secret);

        return hash_equals($computed, $signatureHeader);
    }
}
