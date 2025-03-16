<?php

namespace App\Filament\Admin\Resources\CorrespondenceResource\Pages;

use App\Filament\Admin\Resources\CorrespondenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorrespondence extends EditRecord
{
    protected static string $resource = CorrespondenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
