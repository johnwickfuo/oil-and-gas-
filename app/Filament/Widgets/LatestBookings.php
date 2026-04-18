<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest bookings')
            ->query(
                BookingResource::getEloquentQuery()
                    ->latest()
                    ->limit(5)
            )
            ->defaultPaginationPageOption(5)
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('reference'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('service.title')->default('-'),
                Tables\Columns\TextColumn::make('event_date')->date(),
                Tables\Columns\TextColumn::make('estimated_total')->money('NGN'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Booking::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'pending_payment' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
            ]);
    }
}
