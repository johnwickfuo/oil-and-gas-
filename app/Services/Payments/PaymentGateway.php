<?php

namespace App\Services\Payments;

use App\Models\Booking;

interface PaymentGateway
{
    public function name(): string;

    /**
     * Initialize a transaction with the gateway for the given booking deposit.
     * Returns an array with at least: ['authorization_url' => string, 'reference' => string].
     *
     * @return array{authorization_url: string, reference: string, raw: array}
     */
    public function initialize(Booking $booking): array;

    /**
     * Verify a transaction reference with the gateway.
     * Returns a normalized result:
     *   ['status' => 'success'|'failed'|'pending', 'amount' => float (naira),
     *    'reference' => string, 'raw' => array].
     *
     * @return array{status: string, amount: float, reference: string, raw: array}
     */
    public function verify(string $reference): array;

    /**
     * Validate an incoming webhook signature against its raw body.
     * Return true iff the payload is authentic.
     */
    public function verifyWebhookSignature(string $rawBody, string $signatureHeader): bool;
}
