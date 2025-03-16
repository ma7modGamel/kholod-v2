<?php

namespace App\Filament\Admin\Resources\MyLeaveRequestsResource\Pages;

use App\Filament\Admin\Resources\MyLeaveRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyLeaveRequests extends CreateRecord
{
    protected static string $resource = MyLeaveRequestsResource::class;
}
