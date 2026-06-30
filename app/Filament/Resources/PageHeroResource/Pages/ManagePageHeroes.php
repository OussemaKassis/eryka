<?php

namespace App\Filament\Resources\PageHeroResource\Pages;

use App\Filament\Resources\PageHeroResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePageHeroes extends ManageRecords
{
    protected static string $resource = PageHeroResource::class;

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'En-têtes de page';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
