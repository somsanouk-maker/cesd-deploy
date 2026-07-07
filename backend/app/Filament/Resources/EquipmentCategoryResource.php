<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentCategoryResource\Pages;
use App\Models\EquipmentCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EquipmentCategoryResource extends Resource
{
    protected static ?string $model = EquipmentCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Laboratories & Equipment';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name_en')->label('Name (English)')->required()->maxLength(255),
            TextInput::make('name_lo')->label('Name (Lao)')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')->label('Name (English)')->searchable()->sortable(),
                TextColumn::make('name_lo')->label('Name (Lao)'),
                TextColumn::make('equipment_count')->counts('equipment')->label('Equipment'),
            ])
            ->defaultSort('name_en');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentCategories::route('/'),
            'create' => Pages\CreateEquipmentCategory::route('/create'),
            'edit' => Pages\EditEquipmentCategory::route('/{record}/edit'),
        ];
    }
}
