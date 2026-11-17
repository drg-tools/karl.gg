<?php

namespace App\Filament\Widgets;

use App\User;

class DailyUsers extends DailyActivity
{
    protected ?string $heading = 'New users (last 30 days)';

    protected string $activityModel = User::class;

    protected string $activityLabel = 'Users';
}
