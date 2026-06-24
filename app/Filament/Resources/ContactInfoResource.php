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

    protected static ?string $navigationLabel = 'Contact Info';

    protected static ?string $modelLabel = 'contact info';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'address' => 'Address',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->placeholder('e.g. "Showroom", "Support", "TN"')
                    ->helperText('Optional short tag shown next to the value (a country code for phones, or a site name for addresses).'),
                Forms\Components\TextInput::make('value')
                    ->label(fn (Forms\Get $get): string => match ($get('type')) {
                        'email' => 'Email address',
                        'phone' => 'Phone number',
                        'address' => 'Address',
                        default => 'Value',
                    })
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Show on contact page')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order'),
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
