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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Stock Quantity')
                    ->helperText('How many units are available. Shows as "Out of Stock" on the site when this hits 0.')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\RichEditor::make('detail')->nullable(),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::with('parent')->get()->mapWithKeys(
                        fn (Category $category) => [
                            $category->id => $category->parent
                                ? "{$category->parent->title} › {$category->title}"
                                : $category->title,
                        ]
                    ))
                    ->searchable()
                    ->required(),
                Forms\Components\FileUpload::make('images')
                    ->label('Article Images')
                    ->image()
                    ->disk('public')
                    ->directory('articles')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('price')->money('usd'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => $state <= 0 ? 'Out of stock' : (string) $state),
                Tables\Columns\TextColumn::make('category.title')->label('Category'),
                Tables\Columns\ViewColumn::make('images')
                    ->label('Images')
                    ->view('filament.tables.columns.article-images')
                    ->getStateUsing(fn ($record) => $record->images)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('images', function($q) use ($search) {
                            $q->where('image_path', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
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
