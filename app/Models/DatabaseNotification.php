<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

/**
 * An in-app notification. `php artisan model:prune` keeps the table small by
 * deleting notifications a month after they are read and every notification
 * after three months.
 */
class DatabaseNotification extends BaseDatabaseNotification
{
    use MassPrunable;

    public const DAYS_KEPT_AFTER_READING = 30;

    public const DAYS_KEPT = 90;

    public function prunable(): Builder
    {
        return static::query()
            ->where('read_at', '<', now()->subDays(self::DAYS_KEPT_AFTER_READING))
            ->orWhere('created_at', '<', now()->subDays(self::DAYS_KEPT));
    }
}
