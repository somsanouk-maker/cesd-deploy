<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaboratoryResource\Pages;
use App\Models\Laboratory;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaboratoryResource extends Resource
{
    protected static ?string $model = Laboratory::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Laboratories & Equipment';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identity')
                ->columns(2)
                ->schema([
                    TextInput::make('code')->required()->maxLength(20)->unique(ignoreRecord: true),
                    Select::make('status')
                        ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                        ->default('active')
                        ->required(),
                    TextInput::make('name_en')->label('Name (English)')->required()->maxLength(255),
                    TextInput::make('name_lo')->label('Name (Lao)')->required()->maxLength(255),
                ]),
            Section::make('Description')
                ->columns(2)
                ->schema([
                    Textarea::make('description_en')->label('Description (English)')->rows(3),
                    Textarea::make('description_lo')->label('Description (Lao)')->rows(3),
                    Textarea::make('safety_rules_en')->label('Safety Rules (English)')->rows(3),
                    Textarea::make('safety_rules_lo')->label('Safety Rules (Lao)')->rows(3),
                ]),
            Section::make('Location & Staff')
                ->columns(2)
                ->schema([
                    TextInput::make('building')->maxLength(100),
                    TextInput::make('floor')->maxLength(50),
                    TextInput::make('room_name')->maxLength(150),
                    TextInput::make('location_no')->numeric(),
                    Select::make('responsible_user_id')
                        ->label('Responsible Staff')
                        ->options(fn () => User::role(['lab_staff', 'unit_head'])->pluck('name', 'id'))
                        ->searchable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->badge()->sortable(),
                TextColumn::make('name_en')->label('Name')->searchable()->sortable(),
                TextColumn::make('room_name')->label('Room')->toggleable(),
                TextColumn::make('responsibleUser.name')->label('Responsible Staff'),
                TextColumn::make('equipment_count')->counts('equipment')->label('Equipment'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->defaultSort('name_en');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaboratories::route('/'),
            'create' => Pages\CreateLaboratory::route('/create'),
            'edit' => Pages\EditLaboratory::route('/{record}/edit'),
        ];
    }
}
