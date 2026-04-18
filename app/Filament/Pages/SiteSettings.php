<?php

namespace App\Filament\Pages;

use App\Support\Settings;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    protected const KEYS = [
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'instagram_url',
        'facebook_url',
        'twitter_url',
        'deposit_percentage',
        'business_address',
        'service_area',
    ];

    public function mount(): void
    {
        $this->form->fill([
            'contact_email' => Settings::get('contact_email'),
            'contact_phone' => Settings::get('contact_phone'),
            'whatsapp_number' => Settings::get('whatsapp_number'),
            'instagram_url' => Settings::get('instagram_url'),
            'facebook_url' => Settings::get('facebook_url'),
            'twitter_url' => Settings::get('twitter_url'),
            'deposit_percentage' => Settings::get('deposit_percentage', 30),
            'business_address' => Settings::get('business_address'),
            'service_area' => Settings::get('service_area'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact')->schema([
                    Forms\Components\TextInput::make('contact_email')->email()->maxLength(255),
                    Forms\Components\TextInput::make('contact_phone')->tel()->maxLength(50),
                    Forms\Components\TextInput::make('whatsapp_number')
                        ->maxLength(50)
                        ->helperText('International format without leading + (e.g. 2348012345678).'),
                ])->columns(2),

                Forms\Components\Section::make('Social')->schema([
                    Forms\Components\TextInput::make('instagram_url')->url(),
                    Forms\Components\TextInput::make('facebook_url')->url(),
                    Forms\Components\TextInput::make('twitter_url')->url(),
                ])->columns(2),

                Forms\Components\Section::make('Business')->schema([
                    Forms\Components\TextInput::make('deposit_percentage')
                        ->numeric()->minValue(0)->maxValue(100)->suffix('%')
                        ->default(30),
                    Forms\Components\TextInput::make('business_address')->maxLength(255),
                    Forms\Components\TextInput::make('service_area')->maxLength(255),
                ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (self::KEYS as $key) {
            Settings::set($key, $data[$key] ?? null);
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save')
                ->submit('save'),
        ];
    }
}
