<?php

namespace App\Filament\Resources\EquipmentModResource\Pages;

use App\Filament\Resources\EquipmentModResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEquipmentMod extends ManageRecords
{
    protected static string $resource = EquipmentModResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
