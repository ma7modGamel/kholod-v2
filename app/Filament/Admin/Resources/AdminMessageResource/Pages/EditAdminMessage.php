<?php

namespace App\Filament\Admin\Resources\AdminMessageResource\Pages;

use App\Filament\Admin\Resources\AdminMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminMessage extends EditRecord
{
    protected static string $resource = AdminMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
