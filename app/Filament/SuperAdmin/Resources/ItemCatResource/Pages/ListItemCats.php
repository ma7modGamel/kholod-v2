<?php

namespace App\Filament\SuperAdmin\Resources\ItemCatResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemCatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemCats extends ListRecords
{
    protected static string $resource = ItemCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
