<?php

namespace App\Filament\Widgets;

class DailyUsers extends DailyActivity
{
    protected ?string $heading = 'New users (last 30 days)';
    protected string $activityModel = \App\User::class;
    protected string $activityLabel = 'Users';
}
