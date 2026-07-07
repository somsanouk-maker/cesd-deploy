<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Bookings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_no')->label('Booking No.')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Requester'),
                TextColumn::make('bookableName')->label('Item')->state(fn (Booking $record) => $record->bookableName()),
                TextColumn::make('start_at')->dateTime()->sortable(),
                TextColumn::make('end_at')->dateTime()->sortable(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_advisor', 'pending_staff' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucwords(str_replace('_', ' ', $state))),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_advisor' => 'Pending Advisor',
                        'pending_staff' => 'Pending Staff',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve_advisor')
                    ->label('Approve (Advisor)')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status === 'pending_advisor' && auth()->user()->hasAnyRole(['unit_head', 'director', 'admin']))
                    ->requiresConfirmation()
                    ->action(fn (Booking $record) => $record->approveByAdvisor(auth()->user())),
                Tables\Actions\Action::make('approve_staff')
                    ->label('Approve (Final)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status === 'pending_staff' && auth()->user()->hasAnyRole(['lab_staff', 'unit_head', 'director', 'admin']))
                    ->requiresConfirmation()
                    ->action(fn (Booking $record) => $record->approveByStaff(auth()->user())),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Booking $record) => in_array($record->status, ['pending_advisor', 'pending_staff'], true))
                    ->form([
                        Textarea::make('note')->label('Reason')->required()->rows(3),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $stage = $record->status === 'pending_advisor' ? 'advisor' : 'staff';
                        $record->reject(auth()->user(), $data['note'], $stage);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBookings::route('/'),
        ];
    }
}
