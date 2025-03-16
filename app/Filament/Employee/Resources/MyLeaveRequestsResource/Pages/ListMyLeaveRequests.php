<?php

namespace App\Filament\Employee\Resources\MyLeaveRequestsResource\Pages;

use App\Filament\Employee\Resources\MyLeaveRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyLeaveRequests extends ListRecords
{
    protected static string $resource = MyLeaveRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}