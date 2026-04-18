<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('excerpt')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('cover_image')
                    ->image()->imageEditor()->directory('recipes')->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Details')->schema([
                Forms\Components\TextInput::make('prep_time')->numeric()->required()->suffix('min'),
                Forms\Components\TextInput::make('cook_time')->numeric()->required()->suffix('min'),
                Forms\Components\TextInput::make('servings')->numeric()->required(),
                Forms\Components\Select::make('difficulty')
                    ->options(Recipe::DIFFICULTIES)
                    ->required(),
                Forms\Components\TextInput::make('meal_type')->required()->maxLength(100),
                Forms\Components\DateTimePicker::make('published_at'),
            ])->columns(2),

            Forms\Components\Section::make('Ingredients')->schema([
                Forms\Components\Repeater::make('ingredients')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                        Forms\Components\TextInput::make('quantity')->required(),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Instructions')->schema([
                Forms\Components\Repeater::make('instructions')
                    ->simple(
                        Forms\Components\Textarea::make('step')
                            ->required()
                            ->rows(2)
                    )
                    ->defaultItems(1)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->toggleable(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('meal_type')->badge()->searchable(),
                Tables\Columns\TextColumn::make('difficulty')->badge(),
                Tables\Columns\TextColumn::make('prep_time')->suffix(' min'),
                Tables\Columns\TextColumn::make('cook_time')->suffix(' min'),
                Tables\Columns\TextColumn::make('servings'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()->sortable()->placeholder('Draft'),
                Tables\Columns\TextColumn::make('views')->numeric()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty')->options(Recipe::DIFFICULTIES),
                Tables\Filters\SelectFilter::make('meal_type')
                    ->options(fn () => Recipe::query()->pluck('meal_type', 'meal_type')->all()),
                Tables\Filters\Filter::make('published')
                    ->query(fn ($q) => $q->whereNotNull('published_at')),
                Tables\Filters\Filter::make('draft')
                    ->query(fn ($q) => $q->whereNull('published_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
