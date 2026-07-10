<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandResource\Pages;
use App\Models\Command;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommandResource extends Resource
{
    protected static ?string $model = Command::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = -1;

    protected static ?string $navigationLabel = 'Commandes';

    protected static ?string $modelLabel = 'ligne de commande';

    protected static ?string $pluralModelLabel = 'lignes de commande';

    protected static ?string $breadcrumb = 'Lignes de commande';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('article.title')->label('Article')->disabled(),
                Forms\Components\TextInput::make('color')->label('Couleur')->disabled(),
                Forms\Components\TextInput::make('quantity')->label('Quantité')->disabled(),
                Forms\Components\TextInput::make('customer_first_name')->label('Prénom')->disabled(),
                Forms\Components\TextInput::make('customer_last_name')->label('Nom')->disabled(),
                Forms\Components\TextInput::make('email')->label('E-mail')->disabled(),
                Forms\Components\TextInput::make('phone_number')->label('Téléphone')->disabled(),
                Forms\Components\TextInput::make('city')->label('Ville')->disabled(),
                Forms\Components\Textarea::make('address')->label('Adresse')->disabled()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['article.images']))
            ->columns([
                Tables\Columns\ImageColumn::make('article_image')
                    ->label('')
                    ->state(fn (Command $record) => $record->article?->images?->first()?->image_path)
                    ->disk('public')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('article.title')
                    ->label('Article')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')->label('Couleur')->placeholder('—'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantité'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Sous-total')
                    ->state(fn (Command $record): float => $record->quantity * ($record->article?->price ?? 0))
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2) . ' DT'),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Livraison (par commande)')
                    ->formatStateUsing(fn (?float $state): string => number_format($state ?? 0, 2) . ' DT'),
                Tables\Columns\TextColumn::make('customer_first_name')
                    ->label('Prénom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_last_name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')->label('Ville')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Téléphone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('group_id')
                    ->label('Commande')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function (Command $record): string {
                        static $numberByGroup = [];
                        static $nextNumber = 1;

                        $number = $numberByGroup[$record->group_id] ??= $nextNumber++;

                        return sprintf(
                            'Commande n°%d — %s %s — %s',
                            $number,
                            $record->customer_first_name,
                            $record->customer_last_name,
                            $record->created_at->locale('fr')->translatedFormat('j M Y H:i'),
                        );
                    })
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy('sort_order', $direction))
                    ->collapsible(),
            ])
            ->defaultGroup('group_id')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordClasses(function (Command $record) {
                static $colorByGroup = [];
                static $nextColor = 0;

                $color = $colorByGroup[$record->group_id] ??= $nextColor++ % 2;

                return $color === 0 ? '' : 'order-group-tint';
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommands::route('/'),
            'view' => Pages\ViewCommand::route('/{record}'),
        ];
    }
}
