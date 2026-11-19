<?php

namespace App\Console\Commands;

use App\Loadout;
use App\Patch;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:backfill-loadout-patches {--apply : Save changes; default is a read-only preview}')]
#[Description('Assign the current patch to loadouts created on or after its launch')]
class BackfillLoadoutPatches extends Command
{
    public function handle(): int
    {
        $patch = Patch::current();
        $launchedAt = $patch?->launched_at;

        if ($launchedAt === null || $launchedAt->isFuture()) {
            $this->error('The current patch needs a launch timestamp at or before the current time.');

            return self::FAILURE;
        }

        $loadouts = Loadout::query()
            ->where('created_at', '>=', $launchedAt)
            ->where('patch_id', '!=', $patch->id);

        $this->info("Patch {$patch->patch_title} ({$patch->label}), launched {$launchedAt}.");

        if ($this->option('apply')) {
            $updated = $loadouts->toBase()->update(['patch_id' => $patch->id]);
            $this->info("Updated {$updated} loadouts.");
        } else {
            $eligible = $loadouts->count();
            $this->info("Preview: {$eligible} loadouts would be updated. Use --apply to save changes.");
        }

        return self::SUCCESS;
    }
}
