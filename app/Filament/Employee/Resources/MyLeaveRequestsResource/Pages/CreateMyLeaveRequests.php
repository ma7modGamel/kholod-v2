<?php

namespace App\Filament\Employee\Resources\MyLeaveRequestsResource\Pages;

use App\Filament\Employee\Resources\MyLeaveRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyLeaveRequests extends CreateRecord
{
    protected static string $resource = MyLeaveRequestsResource::class;
}
