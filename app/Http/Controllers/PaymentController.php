<?php

namespace App\Http\Controllers;

use App\Events\PaymentSucceeded;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(protected PaymentGatewayManager $gateways)
    {
    }

    public function choose(string $reference): Response
    {
        $booking = Booking::where('reference', $reference)->firstOrFail();

        return Inertia::render('Payment/Choose', [
            'booking' => [
                'reference' => $booking->reference,
                'name' => $booking->name,
                'deposit_amount' => (float) $booking->deposit_amount,
                'estimated_total' => (float) $booking->estimated_total,
                'payment_status' => $booking->payment_status,
            ],
        ]);
    }

    public function initialize(Request $request, string $reference): RedirectResponse
    {
        $data = $request->validate([
            'gateway' => ['required', Rule::in(['paystack', 'flutterwave'])],
        ]);

        $booking = Booking::where('reference', $reference)->firstOrFail();

        if ($booking->payment_status === 'paid') {
            return redirect()->route('payment.success', [
                'gateway' => 'paystack',
                'reference' => $booking->reference,
            ]);
        }

        $gateway = $this->gateways->driver($data['gateway']);

        try {
            $init = $gateway->initialize($booking);
        } catch (RuntimeException $e) {
            Log::error('payment.initialize.failed', [
                'booking' => $booking->reference,
                'gateway' => $data['gateway'],
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'We could not start the payment. Please try again in a moment.');
        }

        Payment::create([
            'booking_id' => $booking->id,
            'gateway' => $gateway->name(),
            'reference' => $init['reference'],
            'amount' => $booking->deposit_amount,
            'currency' => 'NGN',
            'status' => 'pending',
            'gateway_response' => $init['raw'] ?? [],
        ]);

        return redirect()->away($init['authorization_url']);
    }

    public function callback(Request $request, string $gateway): RedirectResponse
    {
        $reference = $gateway === 'paystack'
            ? (string) $request->query('reference', '')
            : (string) $request->query('tx_ref', '');

        if ($reference === '') {
            return redirect()->route('payment.failed', ['gateway' => $gateway])
                ->with('error', 'Missing payment reference.');
        }

        $driver = $this->gateways->driver($gateway);
        $result = $driver->verify($reference);

        $payment = Payment::where('reference', $reference)->first();

        if ($payment && $result['status'] === 'success') {
            $this->markPaid($payment, $result);
        } elseif ($payment) {
            $payment->update([
                'status' => $result['status'] === 'pending' ? 'pending' : 'failed',
                'gateway_response' => $result['raw'] ?? [],
            ]);
        }

        if ($result['status'] === 'success' && $payment) {
            return redirect()->route('payment.success', [
                'gateway' => $gateway,
                'reference' => $payment->booking->reference,
            ]);
        }

        return redirect()->route('payment.failed', ['gateway' => $gateway])
            ->with('error', 'Payment could not be verified.');
    }

    public function success(string $gateway, string $reference): Response
    {
        $booking = Booking::with('service')->where('reference', $reference)->firstOrFail();

        return Inertia::render('Payment/Success', [
            'gateway' => $gateway,
            'booking' => [
                'reference' => $booking->reference,
                'name' => $booking->name,
                'deposit_amount' => (float) $booking->deposit_amount,
                'estimated_total' => (float) $booking->estimated_total,
                'event_date' => $booking->event_date?->toDateString(),
                'event_time' => substr((string) $booking->event_time, 0, 5),
                'service' => $booking->service?->only(['title', 'slug']),
            ],
        ]);
    }

    public function failed(string $gateway): Response
    {
        return Inertia::render('Payment/Failed', [
            'gateway' => $gateway,
        ]);
    }

    public function webhook(Request $request, string $gateway): HttpResponse|JsonResponse
    {
        $raw = $request->getContent();
        $signature = $gateway === 'paystack'
            ? (string) $request->header('x-paystack-signature', '')
            : (string) $request->header('verif-hash', '');

        $driver = $this->gateways->driver($gateway);

        if (! $driver->verifyWebhookSignature($raw, $signature)) {
            Log::warning('payment.webhook.invalid_signature', [
                'gateway' => $gateway,
                'signature' => $signature,
                'ip' => $request->ip(),
            ]);

            return response()->json(['ok' => false], 401);
        }

        $payload = json_decode($raw, true) ?? [];

        Log::info('payment.webhook.received', [
            'gateway' => $gateway,
            'event' => $payload['event'] ?? null,
        ]);

        $reference = $this->extractReference($gateway, $payload);
        if ($reference === null) {
            return response()->json(['ok' => true, 'note' => 'no reference']);
        }

        $payment = Payment::where('reference', $reference)->first();
        if (! $payment) {
            return response()->json(['ok' => true, 'note' => 'unknown reference']);
        }

        if ($payment->status === 'success') {
            return response()->json(['ok' => true, 'note' => 'already processed']);
        }

        $result = $driver->verify($reference);

        if ($result['status'] === 'success') {
            $this->markPaid($payment, $result);
        } else {
            $payment->update([
                'status' => $result['status'] === 'pending' ? 'pending' : 'failed',
                'gateway_response' => $result['raw'] ?? [],
            ]);
        }

        return response()->json(['ok' => true]);
    }

    protected function extractReference(string $gateway, array $payload): ?string
    {
        if ($gateway === 'paystack') {
            return $payload['data']['reference'] ?? null;
        }

        if ($gateway === 'flutterwave') {
            return $payload['data']['tx_ref'] ?? ($payload['txRef'] ?? null);
        }

        return null;
    }

    protected function markPaid(Payment $payment, array $result): void
    {
        DB::transaction(function () use ($payment, $result) {
            $fresh = Payment::lockForUpdate()->find($payment->id);
            if (! $fresh || $fresh->status === 'success') {
                return;
            }

            $fresh->update([
                'status' => 'success',
                'amount' => $result['amount'] ?: $fresh->amount,
                'paid_at' => now(),
                'gateway_response' => $result['raw'] ?? [],
            ]);

            $booking = $fresh->booking;
            if ($booking) {
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'partial',
                ]);
            }

            PaymentSucceeded::dispatch($fresh);
        });
    }
}
