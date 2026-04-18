<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\NewsletterSubscriber;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $bookingsThisMonth = Booking::query()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $revenueThisMonth = Payment::query()
            ->where('status', 'success')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $pendingBookings = Booking::query()
            ->where('status', 'pending_payment')
            ->count();

        $subscribers = NewsletterSubscriber::query()
            ->whereNull('unsubscribed_at')
            ->count();

        return [
            Stat::make('Bookings this month', number_format($bookingsThisMonth))
                ->icon('heroicon-o-calendar-days')
                ->color('info'),
            Stat::make('Revenue this month', 'NGN '.number_format((float) $revenueThisMonth, 2))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Pending bookings', number_format($pendingBookings))
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Newsletter subscribers', number_format($subscribers))
                ->icon('heroicon-o-envelope')
                ->color('primary'),
        ];
    }
}
