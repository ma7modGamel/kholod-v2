<?php

namespace App\Filament\Admin\Resources\PendingApprovalsResource\Pages;

use App\Filament\Admin\Resources\PendingApprovalsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendingApprovals extends ListRecords
{
    protected static string $resource = PendingApprovalsResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}