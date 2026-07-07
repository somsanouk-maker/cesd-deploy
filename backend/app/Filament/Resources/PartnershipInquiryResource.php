<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnershipInquiryResource\Pages;
use App\Models\PartnershipInquiry;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnershipInquiryResource extends Resource
{
    protected static ?string $model = PartnershipInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Partnership Inquiries';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Inquiry')
                ->description('Submitted by the visitor (read-only).')
                ->columns(2)
                ->schema([
                    TextInput::make('organization_name')->disabled()->dehydrated(false),
                    TextInput::make('contact_name')->disabled()->dehydrated(false),
                    TextInput::make('contact_email')->disabled()->dehydrated(false),
                    TextInput::make('contact_phone')->disabled()->dehydrated(false),
                    TextInput::make('inquiry_type')->disabled()->dehydrated(false),
                    Textarea::make('message')->disabled()->dehydrated(false)->rows(4)->columnSpanFull(),
                ]),
            Section::make('Staff Handling')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->options([
                            'new' => 'New',
                            'in_review' => 'In Review',
                            'accepted' => 'Accepted',
                            'declined' => 'Declined',
                        ])
                        ->required(),
                    Textarea::make('staff_notes')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization_name')->searchable()->sortable(),
                TextColumn::make('contact_name')->label('Contact'),
                TextColumn::make('contact_email')->label('Email'),
                TextColumn::make('inquiry_type'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'in_review' => 'warning',
                        'accepted' => 'success',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'in_review' => 'In Review',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePartnershipInquiries::route('/'),
        ];
    }
}
