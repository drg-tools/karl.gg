<?php

namespace App\Listeners;

use App\Events\LoadoutSaving;
use App\Models\Patch;

class AttachPatchToLoadout
{
    /**
     * @var Patch
     */
    private $patch;

    /**
     * Create the event listener.
     */
    public function __construct(Patch $patch)
    {
        $this->patch = $patch;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(LoadoutSaving $event)
    {
        // Get latest patch
        $patch = $this->patch->latest('launched_at')->first();

        // Associate
        if ($patch) {
            $event->loadout->patch()->associate($patch);
        }
    }
}
