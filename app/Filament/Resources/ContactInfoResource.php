<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactInfoResource\Pages;
use App\Filament\Resources\ContactInfoResource\RelationManagers;
use App\Models\ContactInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactInfoResource extends Resource
{
    protected static ?string $model = ContactInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Coordonnées';

    protected static ?string $modelLabel = 'coordonnée';

    protected static ?string $pluralModelLabel = 'coordonnées';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'email' => 'E-mail',
                        'phone' => 'Téléphone',
                        'address' => 'Adresse',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('label')
                    ->label('Étiquette')
                    ->placeholder('ex. « Showroom », « Support », « TN »')
                    ->helperText('Petite étiquette optionnelle affichée à côté de la valeur (un indicatif pays pour les téléphones, ou un nom de site pour les adresses).'),
                Forms\Components\TextInput::make('value')
                    ->label(fn (Forms\Get $get): string => match ($get('type')) {
                        'email' => 'Adresse e-mail',
                        'phone' => 'Numéro de téléphone',
                        'address' => 'Adresse',
                        default => 'Valeur',
                    })
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Afficher sur la page contact')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'email' => 'E-mail',
                        'phone' => 'Téléphone',
                        'address' => 'Adresse',
                        default => ucfirst($state),
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Étiquette')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valeur'),
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
            'index' => Pages\ManageContactInfos::route('/'),
        ];
    }
}
