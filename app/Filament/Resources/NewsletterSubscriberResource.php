<?php

namespace App\Filament\Resources;

use App\Filament\Actions\CsvExportBulkAction;
use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Audience';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Newsletter';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('subscribed_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('subscribed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('unsubscribed_at')->dateTime()->sortable()->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label('Active only')
                    ->query(fn ($q) => $q->whereNull('unsubscribed_at'))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    CsvExportBulkAction::make('newsletter-subscribers.csv', [
                        'email' => 'Email',
                        'name' => 'Name',
                        'subscribed_at' => 'Subscribed At',
                        'unsubscribed_at' => 'Unsubscribed At',
                    ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'view' => Pages\ViewNewsletterSubscriber::route('/{record}'),
        ];
    }
}
