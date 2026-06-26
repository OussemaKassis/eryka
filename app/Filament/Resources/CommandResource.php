<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandResource\Pages;
use App\Models\Command;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommandResource extends Resource
{
    protected static ?string $model = Command::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'order line';

    protected static ?string $pluralModelLabel = 'order lines';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('article.title')->label('Article')->disabled(),
                Forms\Components\TextInput::make('color')->label('Color')->disabled(),
                Forms\Components\TextInput::make('quantity')->disabled(),
                Forms\Components\TextInput::make('customer_first_name')->label('First Name')->disabled(),
                Forms\Components\TextInput::make('customer_last_name')->label('Last Name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone_number')->label('Phone')->disabled(),
                Forms\Components\TextInput::make('city')->disabled(),
                Forms\Components\Textarea::make('address')->disabled()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('article.title')->label('Article'),
                Tables\Columns\TextColumn::make('color')->label('Color')->placeholder('—'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->state(fn (Command $record): float => $record->quantity * ($record->article?->price ?? 0))
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2) . ' DT'),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Shipping (per order)')
                    ->formatStateUsing(fn (?float $state): string => number_format($state ?? 0, 2) . ' DT'),
                Tables\Columns\TextColumn::make('customer_first_name')->label('First Name'),
                Tables\Columns\TextColumn::make('customer_last_name')->label('Last Name'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('group_id')
                    ->label('Order')
                    ->getTitleFromRecordUsing(fn (Command $record): string => sprintf(
                        'Order from %s %s — %s',
                        $record->customer_first_name,
                        $record->customer_last_name,
                        $record->created_at->format('M j, Y H:i'),
                    ))
                    ->collapsible(),
            ])
            ->defaultGroup('group_id')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommands::route('/'),
            'view' => Pages\ViewCommand::route('/{record}'),
        ];
    }
}
