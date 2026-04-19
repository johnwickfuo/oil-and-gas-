<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Mail\PaymentAdminNotificationMail;
use App\Support\Settings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfPayment implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        $recipient = Settings::get('contact_email', config('mail.from.address'));

        if (! $recipient) {
            return;
        }

        Mail::to($recipient)->send(new PaymentAdminNotificationMail($event->payment));
    }
}
