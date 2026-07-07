<?php

namespace App\Filament\Pages;

use App\Models\AboutContent;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageAboutContent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'About Page Content';

    protected static ?string $title = 'About Page Content';

    protected static string $view = 'filament.pages.manage-about-content';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'director']) ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(AboutContent::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Page Title')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_en')->label('Title (English)'),
                        TextInput::make('title_lo')->label('Title (Lao)'),
                    ]),
                Section::make('Background')
                    ->columns(2)
                    ->schema([
                        Textarea::make('background_en')->label('Background (English)')->rows(4),
                        Textarea::make('background_lo')->label('Background (Lao)')->rows(4),
                    ]),
                Section::make('Vision & Mission')
                    ->columns(2)
                    ->schema([
                        Textarea::make('vision_en')->label('Vision (English)')->rows(3),
                        Textarea::make('vision_lo')->label('Vision (Lao)')->rows(3),
                        Textarea::make('mission_en')->label('Mission (English)')->rows(3),
                        Textarea::make('mission_lo')->label('Mission (Lao)')->rows(3),
                    ]),
                Section::make('Objectives')
                    ->description('Shown as a bullet list on the About page.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('objective1_en')->label('Objective 1 (English)'),
                        TextInput::make('objective1_lo')->label('Objective 1 (Lao)'),
                        TextInput::make('objective2_en')->label('Objective 2 (English)'),
                        TextInput::make('objective2_lo')->label('Objective 2 (Lao)'),
                        TextInput::make('objective3_en')->label('Objective 3 (English)'),
                        TextInput::make('objective3_lo')->label('Objective 3 (Lao)'),
                        TextInput::make('objective4_en')->label('Objective 4 (English)'),
                        TextInput::make('objective4_lo')->label('Objective 4 (Lao)'),
                    ]),
                Section::make('Organization Structure')
                    ->columns(2)
                    ->schema([
                        TextInput::make('org_director_en')->label('Director (English)'),
                        TextInput::make('org_director_lo')->label('Director (Lao)'),
                        TextInput::make('org_deputy_director_en')->label('Deputy Director (English)'),
                        TextInput::make('org_deputy_director_lo')->label('Deputy Director (Lao)'),
                        TextInput::make('org_admin_en')->label('Administration Unit (English)'),
                        TextInput::make('org_admin_lo')->label('Administration Unit (Lao)'),
                        TextInput::make('org_technical_en')->label('Technical Unit (English)'),
                        TextInput::make('org_technical_lo')->label('Technical Unit (Lao)'),
                        TextInput::make('org_innovation_en')->label('Innovation Unit (English)'),
                        TextInput::make('org_innovation_lo')->label('Innovation Unit (Lao)'),
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
        AboutContent::current()->update($this->form->getState());

        Notification::make()->title('About page content saved')->success()->send();
    }
}
