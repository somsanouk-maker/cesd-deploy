<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('author_id')->default(fn () => auth()->id()),
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('title_en')->label('Title (English)')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                    TextInput::make('title_lo')->label('Title (Lao)')->required()->maxLength(255)->columnSpanFull(),
                    TextInput::make('excerpt_en')->label('Excerpt (English)')->maxLength(255),
                    TextInput::make('excerpt_lo')->label('Excerpt (Lao)')->maxLength(255),
                    RichEditor::make('body_en')->label('Body (English)')->columnSpanFull(),
                    RichEditor::make('body_lo')->label('Body (Lao)')->columnSpanFull(),
                    FileUpload::make('cover_image')->image()->directory('news'),
                    DateTimePicker::make('published_at')->label('Publish At')->native(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->toggleable(),
                TextColumn::make('title_en')->label('Title')->searchable()->sortable(),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
