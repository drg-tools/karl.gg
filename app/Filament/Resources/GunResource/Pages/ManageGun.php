<?php

namespace App\Filament\Resources\GunResource\Pages;

use App\Filament\Resources\GunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGun extends ManageRecords
{
    protected static string $resource = GunResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
