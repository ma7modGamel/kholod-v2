<?php

namespace App\Filament\Admin\Resources\MyLeaveRequestsResource\Pages;

use App\Filament\Admin\Resources\MyLeaveRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyLeaveRequests extends EditRecord
{
    protected static string $resource = MyLeaveRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
