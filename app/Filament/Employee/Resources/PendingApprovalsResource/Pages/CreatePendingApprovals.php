<?php

namespace App\Filament\Employee\Resources\PendingApprovalsResource\Pages;

use App\Filament\Employee\Resources\PendingApprovalsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendingApprovals extends CreateRecord
{
    protected static string $resource = PendingApprovalsResource::class;
}
