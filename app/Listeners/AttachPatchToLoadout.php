<?php

namespace App\Listeners;

use App\Events\LoadoutSaving;
use App\Patch;

class AttachPatchToLoadout
{
    /**
     * @var Patch
     */
    private $patch;

    /**
     * Create the event listener.
     *
     * @param  Patch  $patch
     */
    public function __construct(Patch $patch)
    {
        $this->patch = $patch;
    }

    /**
     * Handle the event.
     *
     * @param  LoadoutSaving  $event
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
