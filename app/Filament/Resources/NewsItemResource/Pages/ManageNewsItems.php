<?php

namespace App\Filament\Resources\NewsItemResource\Pages;

use App\Filament\Resources\NewsItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNewsItems extends ManageRecords
{
    protected static string $resource = NewsItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
