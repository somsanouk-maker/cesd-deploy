<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string $view = 'filament.pages.manage-site-settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'director']) ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->only([
            'contact_email', 'contact_phone', 'address_en', 'address_lo', 'facebook_url',
        ]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Information')
                    ->description('Shown in the site footer and Contact page.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->label('Contact Email')->email(),
                        TextInput::make('contact_phone')->label('Contact Phone'),
                        TextInput::make('address_en')->label('Address (English)')->columnSpanFull(),
                        TextInput::make('address_lo')->label('Address (Lao)')->columnSpanFull(),
                        TextInput::make('facebook_url')->label('Facebook URL')->url(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Save')->submit('save'),
        ];
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()->title('Site settings saved')->success()->send();
    }
}
