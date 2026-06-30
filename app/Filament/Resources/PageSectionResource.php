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

    protected static ?string $navigationLabel = 'Sections de page';

    protected static ?string $modelLabel = 'section de page';

    protected static ?string $pluralModelLabel = 'sections de page';

    protected static ?string $breadcrumb = 'Sections de page';

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
                    ->label('Titre')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->label('Texte')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->helperText('Optionnel. Laissez vide pour afficher cette section comme un bandeau de texte pleine largeur plutôt qu\'un bloc texte + image.')
                    ->image()
                    ->disk('public')
                    ->directory('page-sections')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Afficher sur la page')
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
                    ->label('Titre'),
                Tables\Columns\TextColumn::make('body')
                    ->label('Texte')
                    ->limit(60),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordre'),
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
