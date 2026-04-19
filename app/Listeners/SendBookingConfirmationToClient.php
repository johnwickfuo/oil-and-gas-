<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\BookingConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationToClient implements ShouldQueue
{
    public function handle(BookingCreated $event): void
    {
        Mail::to($event->booking->email)
            ->send(new BookingConfirmationMail($event->booking));
    }
}
