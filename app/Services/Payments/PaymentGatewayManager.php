<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class PaymentGatewayManager
{
    public function __construct(
        protected PaystackGateway $paystack,
        protected FlutterwaveGateway $flutterwave,
    ) {
    }

    public function driver(string $name): PaymentGateway
    {
        return match (strtolower($name)) {
            'paystack' => $this->paystack,
            'flutterwave' => $this->flutterwave,
            default => throw new InvalidArgumentException("Unknown payment gateway [{$name}]."),
        };
    }
}
