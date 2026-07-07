<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('name_en')->label('Name (English)')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                    TextInput::make('name_lo')->label('Name (Lao)')->required()->maxLength(255),
                    Select::make('category')
                        ->options([
                            'testing' => 'Testing',
                            'inspection' => 'Inspection',
                            'performance_test' => 'Performance Test',
                            'joint_rd' => 'Joint R&D',
                            'consulting' => 'Consulting',
                            'training' => 'Training',
                            'facility_booking' => 'Facility Booking',
                        ])
                        ->required(),
                    Textarea::make('description_en')->label('Description (English)')->rows(3)->columnSpanFull(),
                    Textarea::make('description_lo')->label('Description (Lao)')->rows(3)->columnSpanFull(),
                    Toggle::make('is_active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')->label('Name')->searchable()->sortable(),
                TextColumn::make('category')->badge(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('name_en');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
