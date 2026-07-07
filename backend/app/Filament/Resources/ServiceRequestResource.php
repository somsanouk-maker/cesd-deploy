<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceRequestResource\Pages;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Service Requests';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Submitted Request')
                ->description('Original details submitted by the requester (read-only).')
                ->columns(2)
                ->schema([
                    TextInput::make('request_no')->disabled()->dehydrated(false),
                    Placeholder::make('service')
                        ->label('Service')
                        ->content(fn (?ServiceRequest $record): string => $record?->service?->name_en ?? '—'),
                    TextInput::make('title')->disabled()->dehydrated(false)->columnSpanFull(),
                    Textarea::make('description')->disabled()->dehydrated(false)->columnSpanFull()->rows(3),
                    Textarea::make('sample_information')->disabled()->dehydrated(false)->columnSpanFull(),
                    TextInput::make('contact_name')->disabled()->dehydrated(false),
                    TextInput::make('contact_email')->disabled()->dehydrated(false),
                    TextInput::make('contact_phone')->disabled()->dehydrated(false),
                    TextInput::make('organization')->disabled()->dehydrated(false),
                ]),
            Section::make('Staff Handling')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->options([
                            'submitted' => 'Submitted',
                            'under_review' => 'Under Review',
                            'accepted' => 'Accepted',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'rejected' => 'Rejected',
                        ])
                        ->required(),
                    Select::make('assigned_staff_id')
                        ->label('Assigned Staff')
                        ->options(fn () => User::role(['lab_staff', 'unit_head'])->pluck('name', 'id'))
                        ->searchable(),
                    Textarea::make('staff_notes')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Quotation')
                ->description('Use the "Send Quotation" button above to set or update this.')
                ->columns(3)
                ->schema([
                    Placeholder::make('quotation_status_display')
                        ->label('Status')
                        ->content(fn (?ServiceRequest $record): string => $record ? ucfirst($record->quotation_status) : '—'),
                    Placeholder::make('quoted_amount_display')
                        ->label('Amount')
                        ->content(fn (?ServiceRequest $record): string => $record?->quoted_amount ? 'LAK '.number_format((float) $record->quoted_amount, 2) : '—'),
                    Placeholder::make('quoted_by_display')
                        ->label('Quoted By')
                        ->content(fn (?ServiceRequest $record): string => $record?->quotedBy?->name ?? '—'),
                    Placeholder::make('quotation_notes_display')
                        ->label('Notes')
                        ->content(fn (?ServiceRequest $record): string => $record?->quotation_notes ?? '—')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_no')->label('Request No.')->sortable()->searchable(),
                TextColumn::make('title')->limit(40)->searchable(),
                TextColumn::make('service.name_en')->label('Service'),
                TextColumn::make('contact_name')->label('Requester'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'under_review' => 'warning',
                        'accepted', 'in_progress' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('quotation_status')->label('Quotation')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not_quoted' => 'gray',
                        'quoted' => 'warning',
                        'accepted' => 'success',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('assignedStaff.name')->label('Assigned To'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'accepted' => 'Accepted',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('quotation_status')
                    ->label('Quotation')
                    ->options([
                        'not_quoted' => 'Not Quoted',
                        'quoted' => 'Quoted',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'edit' => Pages\EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
