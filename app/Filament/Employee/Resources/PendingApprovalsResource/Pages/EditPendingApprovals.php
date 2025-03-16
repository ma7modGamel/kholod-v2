<?php

namespace App\Filament\Employee\Resources\PendingApprovalsResource\Pages;

use App\Filament\Employee\Resources\PendingApprovalsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendingApprovals extends EditRecord
{
    protected static string $resource = PendingApprovalsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
