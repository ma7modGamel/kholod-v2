<?php

namespace App\Filament\SuperAdmin\Resources\ItemCatResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemCatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemCat extends EditRecord
{
    protected static string $resource = ItemCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
