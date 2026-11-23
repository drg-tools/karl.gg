<?php

namespace App\Filament\Widgets;

use App\Models\Loadout;

class DailyUpdatedLoadouts extends DailyActivity
{
    protected ?string $heading = 'Updated loadouts (last 30 days)';

    protected string $activityModel = Loadout::class;

    protected string $dateColumn = 'updated_at';

    protected string $activityLabel = 'Loadouts';
}
