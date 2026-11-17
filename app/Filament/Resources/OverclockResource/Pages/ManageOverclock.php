<?php

namespace App\Filament\Resources\OverclockResource\Pages;

use App\Filament\Resources\OverclockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOverclock extends ManageRecords
{
    protected static string $resource = OverclockResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
