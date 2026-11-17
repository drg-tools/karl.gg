<?php

namespace App\Filament\Widgets;

use App\Loadout;

class DailyLoadouts extends DailyActivity
{
    protected ?string $heading = 'New loadouts (last 30 days)';

    protected string $activityModel = Loadout::class;

    protected string $activityLabel = 'Loadouts';
}
