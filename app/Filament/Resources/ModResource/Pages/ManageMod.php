<?php

namespace App\Filament\Resources\ModResource\Pages;

use App\Filament\Resources\ModResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMod extends ManageRecords
{
    protected static string $resource = ModResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
