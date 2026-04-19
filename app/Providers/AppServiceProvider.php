<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\PaymentSucceeded;
use App\Listeners\NotifyAdminOfNewBooking;
use App\Listeners\NotifyAdminOfPayment;
use App\Listeners\SendBookingConfirmationToClient;
use App\Listeners\SendReceiptToClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Event::listen(BookingCreated::class, SendBookingConfirmationToClient::class);
        Event::listen(BookingCreated::class, NotifyAdminOfNewBooking::class);
        Event::listen(PaymentSucceeded::class, SendReceiptToClient::class);
        Event::listen(PaymentSucceeded::class, NotifyAdminOfPayment::class);

        RateLimiter::for('bookings', fn (Request $request) => Limit::perHour(5)->by($request->ip()));
        RateLimiter::for('contact', fn (Request $request) => Limit::perHour(10)->by($request->ip()));
        RateLimiter::for('downloads', fn (Request $request) => Limit::perHour(30)->by($request->ip()));
        RateLimiter::for('newsletter', fn (Request $request) => Limit::perHour(10)->by($request->ip()));
    }
}
