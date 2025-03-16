<?php

namespace App\Filament\SuperAdmin\Resources\MailLogResource\Pages;

use App\Filament\SuperAdmin\Resources\MailLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailLogs extends ListRecords
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
