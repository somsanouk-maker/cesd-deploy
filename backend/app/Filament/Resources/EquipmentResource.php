<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Laboratory;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Laboratories & Equipment';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identity')
                ->columns(2)
                ->schema([
                    TextInput::make('code')->required()->maxLength(50)->unique(ignoreRecord: true),
                    Select::make('parent_id')
                        ->label('Parent Equipment (if this is an accessory)')
                        ->relationship('parent', 'name_en')
                        ->searchable()
                        ->nullable(),
                    TextInput::make('name_en')->label('Name (English)')->required()->maxLength(255),
                    TextInput::make('name_lo')->label('Name (Lao)')->required()->maxLength(255),
                    Toggle::make('is_accessory')->label('This is an accessory item'),
                ]),
            Section::make('Classification')
                ->columns(2)
                ->schema([
                    Select::make('laboratory_id')
                        ->label('Laboratory')
                        ->options(fn () => Laboratory::pluck('name_en', 'id'))
                        ->searchable(),
                    Select::make('category_id')
                        ->label('Category')
                        ->options(fn () => EquipmentCategory::pluck('name_en', 'id'))
                        ->searchable(),
                    Select::make('responsible_user_id')
                        ->label('Responsible Staff')
                        ->options(fn () => User::role(['lab_staff', 'unit_head'])->pluck('name', 'id'))
                        ->searchable(),
                    Select::make('availability_status')
                        ->options([
                            'available' => 'Available',
                            'in_use' => 'In Use',
                            'maintenance' => 'Under Maintenance',
                            'retired' => 'Retired',
                        ])
                        ->default('available')
                        ->required(),
                ]),
            Section::make('Details')
                ->columns(2)
                ->schema([
                    TextInput::make('brand')->maxLength(150),
                    TextInput::make('model')->maxLength(150),
                    TextInput::make('serial_number')->maxLength(150),
                    TextInput::make('shipping_country')->maxLength(100),
                    TextInput::make('unit')->maxLength(50),
                    TextInput::make('quantity')->numeric()->default(1),
                    Textarea::make('specification_en')->label('Specification (English)')->rows(3)->columnSpanFull(),
                    Textarea::make('specification_lo')->label('Specification (Lao)')->rows(3)->columnSpanFull(),
                    Textarea::make('capability_en')->label('Testing Capability (English)')->rows(3)->columnSpanFull(),
                    Textarea::make('capability_lo')->label('Testing Capability (Lao)')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Media')
                ->columns(2)
                ->schema([
                    FileUpload::make('photo')->image()->directory('equipment/photos'),
                    FileUpload::make('manual_file')->directory('equipment/manuals'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->circular()->toggleable(),
                TextColumn::make('code')->badge()->sortable()->searchable(),
                TextColumn::make('name_en')->label('Name')->searchable()->wrap(),
                TextColumn::make('laboratory.name_en')->label('Laboratory')->toggleable(),
                TextColumn::make('category.name_en')->label('Category')->toggleable(),
                TextColumn::make('availability_status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'in_use' => 'warning',
                        'maintenance' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('laboratory_id')
                    ->label('Laboratory')
                    ->options(fn () => Laboratory::pluck('name_en', 'id')),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(fn () => EquipmentCategory::pluck('name_en', 'id')),
                SelectFilter::make('availability_status')
                    ->options([
                        'available' => 'Available',
                        'in_use' => 'In Use',
                        'maintenance' => 'Under Maintenance',
                        'retired' => 'Retired',
                    ]),
            ])
            ->defaultSort('name_en');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
