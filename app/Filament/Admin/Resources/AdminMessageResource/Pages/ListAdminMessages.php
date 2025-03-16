<?php

namespace App\Filament\Admin\Resources\AdminMessageResource\Pages;

use App\Filament\Admin\Resources\AdminMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminMessages extends ListRecords
{
    protected static string $resource = AdminMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
