<?php

namespace App\Filament\Resources\TrainingCourseResource\RelationManagers;

use App\Models\TrainingRegistration;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('organization'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'success',
                        'waitlisted' => 'warning',
                        'attended' => 'info',
                        'no_show', 'cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('registered_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'registered' => 'Registered',
                        'waitlisted' => 'Waitlisted',
                        'attended' => 'Attended',
                        'no_show' => 'No Show',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('mark_attended')
                    ->label('Mark Attended')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (TrainingRegistration $record) => $record->status === 'registered')
                    ->action(fn (TrainingRegistration $record) => $record->update(['status' => 'attended', 'attended_at' => now()])),
                Tables\Actions\Action::make('mark_no_show')
                    ->label('No Show')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (TrainingRegistration $record) => $record->status === 'registered')
                    ->requiresConfirmation()
                    ->action(fn (TrainingRegistration $record) => $record->update(['status' => 'no_show'])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
