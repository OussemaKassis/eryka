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

    protected static ?string $navigationLabel = 'Page Headers';

    protected static ?string $modelLabel = 'page header';

    public const PAGE_OPTIONS = [
        'home' => 'Home',
        'about' => 'About Us',
        'products' => 'Products',
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
                    ->label('Title')
                    ->helperText('Leave empty to use the site default for this page.'),
                Forms\Components\Textarea::make('subtitle')
                    ->label('Subtitle')
                    ->helperText('Leave empty to use the site default for this page.'),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->helperText('Optional. Shown next to the title when the page has no hero slider images.')
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
                Tables\Columns\TextColumn::make('title')->placeholder('— using site default —'),
                Tables\Columns\TextColumn::make('subtitle')->limit(50)->placeholder('— using site default —'),
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
