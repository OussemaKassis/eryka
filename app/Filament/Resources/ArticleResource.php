<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Articles';

    protected static ?string $modelLabel = 'article';

    protected static ?string $pluralModelLabel = 'articles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantité en stock')
                    ->helperText('Nombre d\'unités disponibles. Affiché comme « Rupture de stock » sur le site lorsque ce nombre atteint 0. Ignoré si des quantités par couleur sont renseignées ci-dessous — dans ce cas, leur somme fait foi.')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\RichEditor::make('detail')->label('Détails')->nullable(),
                Forms\Components\Select::make('category_id')
                    ->label('Catégorie')
                    ->options(fn () => Category::with('parent')->get()->mapWithKeys(
                        fn (Category $category) => [
                            $category->id => $category->parent
                                ? "{$category->parent->title} › {$category->title}"
                                : $category->title,
                        ]
                    ))
                    ->searchable()
                    ->required(),
                Forms\Components\Repeater::make('images')
                    ->relationship('images')
                    ->label('Images de l\'article (jusqu\'à 3, chacune avec sa couleur)')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('articles')
                            ->required(),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Couleur'),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantité pour cette couleur')
                            ->helperText('Ex : 3 pour cette couleur. Si au moins une couleur a une quantité, le stock du site utilise leur somme et ignore le champ « Quantité en stock » ci-dessus.')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])
                    ->columns(3)
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->maxItems(3)
                    ->addActionLabel('Ajouter une image')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N°')
                    ->getStateUsing(fn ($rowLoop): int => $rowLoop->iteration),
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2) . ' DT'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->sortable()
                    ->getStateUsing(fn ($record): int => $record->effective_quantity)
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => $state <= 0 ? 'Rupture de stock' : (string) $state),
                Tables\Columns\TextColumn::make('category.title')->label('Catégorie'),
                Tables\Columns\ViewColumn::make('images')
                    ->label('Images')
                    ->view('filament.tables.columns.article-images')
                    ->getStateUsing(fn ($record) => $record->images)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('images', function($q) use ($search) {
                            $q->where('image_path', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
