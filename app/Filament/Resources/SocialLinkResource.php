<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Filament\Resources\SocialLinkResource\RelationManagers;
use App\Models\SocialLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Réseaux sociaux';

    protected static ?string $modelLabel = 'réseau social';

    protected static ?string $pluralModelLabel = 'réseaux sociaux';

    protected static ?string $breadcrumb = 'Réseaux sociaux';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('platform')
                    ->label('Plateforme')
                    ->options([
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'twitter' => 'Twitter / X',
                        'pinterest' => 'Pinterest',
                        'youtube' => 'YouTube',
                        'linkedin' => 'LinkedIn',
                        'tiktok' => 'TikTok',
                        'whatsapp' => 'WhatsApp',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->label('URL du profil / de la page')
                    ->url()
                    ->required()
                    ->placeholder('https://...'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Afficher dans le pied de page')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('Plateforme')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->url(fn (SocialLink $record): string => $record->url, true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordre'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                //
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
            'index' => Pages\ManageSocialLinks::route('/'),
        ];
    }
}
