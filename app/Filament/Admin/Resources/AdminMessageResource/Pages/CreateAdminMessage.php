<?php

namespace App\Filament\Admin\Resources\AdminMessageResource\Pages;

use App\Filament\Admin\Resources\AdminMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminMessage extends CreateRecord
{
    protected static string $resource = AdminMessageResource::class;
}
