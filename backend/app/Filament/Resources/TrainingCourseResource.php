<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingCourseResource\Pages;
use App\Filament\Resources\TrainingCourseResource\RelationManagers;
use App\Models\TrainingCourse;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrainingCourseResource extends Resource
{
    protected static ?string $model = TrainingCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Training Courses';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('title_en')->label('Title (English)')->required()->maxLength(255),
                    TextInput::make('title_lo')->label('Title (Lao)')->required()->maxLength(255),
                    Textarea::make('description_en')->label('Description (English)')->rows(3)->columnSpanFull(),
                    Textarea::make('description_lo')->label('Description (Lao)')->rows(3)->columnSpanFull(),
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date'),
                    TextInput::make('capacity')->numeric()->minValue(1),
                    TextInput::make('fee')->numeric()->minValue(0)->prefix('LAK'),
                    Select::make('mode')
                        ->options([
                            'in_person' => 'In Person',
                            'online' => 'Online',
                            'hybrid' => 'Hybrid',
                        ]),
                    Toggle::make('is_active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_en')->label('Title')->searchable()->sortable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('registered')->label('Registered')
                    ->state(fn (TrainingCourse $record) => $record->activeRegistrationsCount().($record->capacity ? " / {$record->capacity}" : '')),
                TextColumn::make('mode')->badge(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RegistrationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingCourses::route('/'),
            'create' => Pages\CreateTrainingCourse::route('/create'),
            'edit' => Pages\EditTrainingCourse::route('/{record}/edit'),
        ];
    }
}
