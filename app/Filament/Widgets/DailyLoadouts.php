<?php

namespace App\Filament\Widgets;

class DailyLoadouts extends DailyActivity
{
    protected ?string $heading = 'New loadouts (last 30 days)';
    protected string $activityModel = \App\Loadout::class;
    protected string $activityLabel = 'Loadouts';
}
