<?php

namespace App\Filament\SuperAdmin\Resources\MailLogResource\Pages;

use App\Filament\SuperAdmin\Resources\MailLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailLog extends EditRecord
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
