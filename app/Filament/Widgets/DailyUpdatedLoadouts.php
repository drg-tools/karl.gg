<?php

namespace App\Filament\Widgets;

class DailyUpdatedLoadouts extends DailyActivity
{
    protected ?string $heading = 'Updated loadouts (last 30 days)';
    protected string $activityModel = \App\Loadout::class;
    protected string $dateColumn = 'updated_at';
    protected string $activityLabel = 'Loadouts';
}
