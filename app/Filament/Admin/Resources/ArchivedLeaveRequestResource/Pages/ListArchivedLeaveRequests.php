<?php

namespace App\Filament\Admin\Resources\ArchivedLeaveRequestResource\Pages;

use App\Filament\Admin\Resources\ArchivedLeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArchivedLeaveRequests extends ListRecords
{
    protected static string $resource = ArchivedLeaveRequestResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}