<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Client')->schema([
                Forms\Components\TextInput::make('reference')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Auto-generated. Leave blank on create.'),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->tel()->required(),
            ])->columns(2),

            Forms\Components\Section::make('Event')->schema([
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'title')
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('event_date')->required(),
                Forms\Components\TimePicker::make('event_time')->required()->seconds(false),
                Forms\Components\TextInput::make('guests')->numeric()->required(),
                Forms\Components\TextInput::make('location')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('dietary_notes')->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('special_requests')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Money & Status')->schema([
                Forms\Components\TextInput::make('estimated_total')
                    ->required()->numeric()->prefix('NGN'),
                Forms\Components\TextInput::make('deposit_amount')
                    ->required()->numeric()->prefix('NGN'),
                Forms\Components\Select::make('status')
                    ->options(Booking::STATUSES)
                    ->required()
                    ->default('pending_payment'),
                Forms\Components\Select::make('payment_status')
                    ->options(Booking::PAYMENT_STATUSES)
                    ->required()
                    ->default('unpaid'),
                Forms\Components\Textarea::make('admin_notes')->rows(3)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('event_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('service.title')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('event_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('event_time')->time('H:i'),
                Tables\Columns\TextColumn::make('guests')->numeric(),
                Tables\Columns\TextColumn::make('estimated_total')->money('NGN')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Booking::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'pending_payment' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Booking::PAYMENT_STATUSES[$state] ?? $state),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(Booking::STATUSES),
                Tables\Filters\SelectFilter::make('payment_status')->options(Booking::PAYMENT_STATUSES),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_confirmed')
                    ->label('Mark as Confirmed')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(fn (Booking $r) => $r->status !== 'confirmed')
                    ->action(fn (Booking $r) => $r->update(['status' => 'confirmed']))
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Booking $r) => $r->status !== 'completed')
                    ->action(fn (Booking $r) => $r->update(['status' => 'completed']))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
