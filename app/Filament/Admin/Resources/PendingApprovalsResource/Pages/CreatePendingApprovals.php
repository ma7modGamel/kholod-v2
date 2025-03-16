<?php

namespace App\Filament\Admin\Resources\PendingApprovalsResource\Pages;

use App\Filament\Admin\Resources\PendingApprovalsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendingApprovals extends CreateRecord
{
    protected static string $resource = PendingApprovalsResource::class;
}
