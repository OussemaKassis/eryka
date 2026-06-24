<?php

namespace App\Filament\Resources\PageHeroResource\Pages;

use App\Filament\Resources\PageHeroResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePageHeroes extends ManageRecords
{
    protected static string $resource = PageHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
