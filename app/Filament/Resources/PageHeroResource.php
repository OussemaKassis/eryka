<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageHeroResource\Pages;
use App\Models\PageHero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageHeroResource extends Resource
{
    protected static ?string $model = PageHero::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'En-têtes de page';

    protected static ?string $modelLabel = 'en-tête de page';

    protected static ?string $pluralModelLabel = 'en-têtes de page';

    protected static ?string $breadcrumb = 'En-têtes de page';

    public const PAGE_OPTIONS = [
        'home' => 'Accueil',
        'about' => 'À propos',
        'products' => 'Produits',
        'contact' => 'Contact',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('page_key')
                    ->label('Page')
                    ->options(self::PAGE_OPTIONS)
                    ->disabledOn('edit')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->helperText('Laissez vide pour utiliser le titre par défaut du site pour cette page.'),
                Forms\Components\Textarea::make('subtitle')
                    ->label('Sous-titre')
                    ->helperText('Laissez vide pour utiliser le sous-titre par défaut du site pour cette page.'),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->helperText('Optionnel. Affichée à côté du titre lorsque la page n\'a pas d\'images de diaporama.')
                    ->image()
                    ->disk('public')
                    ->directory('page-heroes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_key')
                    ->label('Page')
                    ->formatStateUsing(fn (string $state): string => self::PAGE_OPTIONS[$state] ?? $state)
                    ->badge(),
                Tables\Columns\TextColumn::make('title')->label('Titre')->placeholder('— valeur par défaut du site —'),
                Tables\Columns\TextColumn::make('subtitle')->label('Sous-titre')->limit(50)->placeholder('— valeur par défaut du site —'),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePageHeroes::route('/'),
        ];
    }
}
