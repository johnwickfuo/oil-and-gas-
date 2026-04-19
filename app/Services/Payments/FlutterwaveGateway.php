<?php

namespace App\Services\Payments;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FlutterwaveGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'flutterwave';
    }

    public function initialize(Booking $booking): array
    {
        $secret = (string) config('flutterwave.secretKey');
        if ($secret === '') {
            throw new RuntimeException('Flutterwave secret key not configured.');
        }

        $txRef = $booking->reference . '-' . strtoupper(bin2hex(random_bytes(3)));

        $payload = [
            'tx_ref' => $txRef,
            'amount' => (float) $booking->deposit_amount,
            'currency' => 'NGN',
            'redirect_url' => route('payment.callback', ['gateway' => 'flutterwave']),
            'customer' => [
                'email' => $booking->email,
                'name' => $booking->name,
                'phonenumber' => $booking->phone,
            ],
            'customizations' => [
                'title' => 'Blue Dine Cuisines',
                'description' => 'Booking deposit for ' . $booking->reference,
            ],
            'meta' => [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->reference,
            ],
        ];

        $response = Http::withToken($secret)
            ->acceptJson()
            ->asJson()
            ->post(config('flutterwave.paymentUrl') . '/payments', $payload);

        Log::info('flutterwave.initialize', [
            'booking' => $booking->reference,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->successful() || strtolower((string) $response->json('status')) !== 'success') {
            throw new RuntimeException('Failed to initialize Flutterwave transaction: ' . $response->body());
        }

        return [
            'authorization_url' => (string) $response->json('data.link'),
            'reference' => $txRef,
            'raw' => $response->json() ?? [],
        ];
    }

    public function verify(string $reference): array
    {
        $secret = (string) config('flutterwave.secretKey');
        if ($secret === '') {
            throw new RuntimeException('Flutterwave secret key not configured.');
        }

        $response = Http::withToken($secret)
            ->acceptJson()
            ->get(config('flutterwave.paymentUrl') . '/transactions/verify_by_reference', [
                'tx_ref' => $reference,
            ]);

        Log::info('flutterwave.verify', [
            'reference' => $reference,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->successful() || strtolower((string) $response->json('status')) !== 'success') {
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
            'status' => $gatewayStatus === 'successful' ? 'success' : ($gatewayStatus === 'pending' ? 'pending' : 'failed'),
            'amount' => (float) ($data['amount'] ?? 0),
            'reference' => (string) ($data['tx_ref'] ?? $reference),
            'raw' => $response->json() ?? [],
        ];
    }

    public function verifyWebhookSignature(string $rawBody, string $signatureHeader): bool
    {
        $expected = (string) config('flutterwave.secretHash');
        if ($expected === '' || $signatureHeader === '') {
            return false;
        }

        return hash_equals($expected, $signatureHeader);
    }
}
