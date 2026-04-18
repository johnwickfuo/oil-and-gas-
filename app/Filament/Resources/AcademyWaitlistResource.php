<?php

namespace App\Filament\Resources;

use App\Filament\Actions\CsvExportBulkAction;
use App\Filament\Resources\AcademyWaitlistResource\Pages;
use App\Models\AcademyWaitlist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AcademyWaitlistResource extends Resource
{
    protected static ?string $model = AcademyWaitlist::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Audience';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Academy Waitlist';

    protected static ?string $label = 'Waitlist Entry';

    protected static ?string $pluralLabel = 'Waitlist Entries';

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
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('interest_level')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => AcademyWaitlist::INTEREST_LEVELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'curious' => 'gray',
                        'serious' => 'info',
                        'ready' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('interest_level')
                    ->options(AcademyWaitlist::INTEREST_LEVELS),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    CsvExportBulkAction::make('academy-waitlist.csv', [
                        'name' => 'Name',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'interest_level' => 'Interest Level',
                        'notes' => 'Notes',
                        'created_at' => 'Joined',
                    ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademyWaitlists::route('/'),
            'view' => Pages\ViewAcademyWaitlist::route('/{record}'),
        ];
    }
}
