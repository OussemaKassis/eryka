<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\ArticleImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

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
                Forms\Components\RichEditor::make('detail')->nullable(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'title')
                    ->required(),
                Forms\Components\FileUpload::make('images')
                    ->label('Article Images')
                    ->image()
                    ->directory('articles')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->saveUploadedFileUsing(function ($file, $get, $set, $record) {
                        $path = $file->store('articles', 'public');
                        
                        if ($record) {
                            $sortOrder = ($record->images()->max('sort_order') ?? 0) + 1;
                            $record->images()->create([
                                'image_path' => str_replace('public/', '', $path),
                                'sort_order' => $sortOrder
                            ]);
                            return null;
                        }
                        
                        return $path;
                    })
                    ->dehydrated(false)
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, $set, $record) {
                        if (!$record) {
                            $set('pending_images', $state);
                        }
                    })
                    ->afterStateHydrated(function (Forms\Set $set, $state) {
                        // This ensures existing images are shown in the form when editing
                        if (is_array($state)) {
                            $set('existing_images', $state);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('price'),
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
    
    public static function afterCreate($record, array $data): void
    {
        if (isset($data['pending_images'])) {
            foreach ($data['pending_images'] as $index => $path) {
                $record->images()->create([
                    'image_path' => str_replace('public/', '', $path),
                    'sort_order' => $index
                ]);
            }
        }
    }
    
    public static function afterSave($record, array $data): void
    {
        // Handle any additional save logic if needed
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
