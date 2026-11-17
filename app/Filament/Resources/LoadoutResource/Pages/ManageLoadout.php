<?php

namespace App\Filament\Resources\LoadoutResource\Pages;

use App\Filament\Resources\LoadoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLoadout extends ManageRecords
{
    protected static string $resource = LoadoutResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
