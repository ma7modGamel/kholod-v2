<?php

namespace App\Filament\Admin\Resources\EmailResource\Pages;

use App\Filament\Admin\Resources\EmailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmail extends CreateRecord
{
    protected static string $resource = EmailResource::class;
}
