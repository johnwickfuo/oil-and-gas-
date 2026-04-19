<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Mail\PaymentReceiptMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReceiptToClient implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        $email = $event->payment->booking?->email;

        if (! $email) {
            return;
        }

        Mail::to($email)->send(new PaymentReceiptMail($event->payment));
    }
}
