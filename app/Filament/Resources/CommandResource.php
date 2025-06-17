<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandResource\Pages;
use App\Filament\Resources\CommandResource\RelationManagers;
use App\Models\Command;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommandResource extends Resource
{
    protected static ?string $model = Command::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('article.title')->label('Article'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('customer_first_name')->label('First Name'),
                Tables\Columns\TextColumn::make('customer_last_name')->label('Last Name'),
                Tables\Columns\TextColumn::make('address')->limit(30),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([] // No actions, readonly
            )
            ->bulkActions([] // No bulk actions, readonly
            );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommands::route('/'),
            'create' => Pages\CreateCommand::route('/create'),
            'edit' => Pages\EditCommand::route('/{record}/edit'),
        ];
    }
}
