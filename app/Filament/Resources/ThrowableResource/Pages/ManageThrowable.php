<?php

namespace App\Filament\Resources\ThrowableResource\Pages;

use App\Filament\Resources\ThrowableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageThrowable extends ManageRecords
{
    protected static string $resource = ThrowableResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
