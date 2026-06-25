<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSectionResource\Pages;
use App\Models\PageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Page Sections';

    protected static ?string $modelLabel = 'page section';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('page_key')
                    ->label('Page')
                    ->options(PageHeroResource::PAGE_OPTIONS)
                    ->default('home')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->label('Text')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->helperText('Optional. Leave empty to show this section as a full-width text band instead of a text + image block.')
                    ->image()
                    ->disk('public')
                    ->directory('page-sections')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Show on the page')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_key')
                    ->label('Page')
                    ->formatStateUsing(fn (string $state): string => PageHeroResource::PAGE_OPTIONS[$state] ?? $state)
                    ->badge(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('body')
                    ->label('Text')
                    ->limit(60),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('page_key')
                    ->label('Page')
                    ->options(PageHeroResource::PAGE_OPTIONS),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePageSections::route('/'),
        ];
    }
}
