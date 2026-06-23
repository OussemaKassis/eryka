<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['images'] = $this->record->images()->pluck('image_path')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        // Filament's multiple FileUpload state is keyed by file UUID, not a
        // sequential index, so re-index before using the position as sort_order.
        $paths = array_values($this->data['images'] ?? []);

        $this->record->images()->whereNotIn('image_path', $paths)->delete();

        foreach ($paths as $index => $path) {
            $this->record->images()->updateOrCreate(
                ['image_path' => $path],
                ['sort_order' => $index],
            );
        }
    }
}
