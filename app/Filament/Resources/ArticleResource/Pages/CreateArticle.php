<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
    {
        // Filament's multiple FileUpload state is keyed by file UUID, not a
        // sequential index, so re-index before using the position as sort_order.
        $paths = array_values($this->data['images'] ?? []);

        foreach ($paths as $index => $path) {
            $this->record->images()->create([
                'image_path' => $path,
                'sort_order' => $index,
            ]);
        }
    }
}
