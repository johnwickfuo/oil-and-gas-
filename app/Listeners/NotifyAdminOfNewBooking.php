<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\NewBookingAdminMail;
use App\Support\Settings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfNewBooking implements ShouldQueue
{
    public function handle(BookingCreated $event): void
    {
        $recipient = Settings::get('contact_email', config('mail.from.address'));

        if (! $recipient) {
            return;
        }

        Mail::to($recipient)->send(new NewBookingAdminMail($event->booking));
    }
}
