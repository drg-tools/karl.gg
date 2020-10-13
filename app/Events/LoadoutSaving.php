<?php

namespace App\Events;

use App\Loadout;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoadoutSaving
{
    use Dispatchable, SerializesModels;

    /**
     * @var Loadout
     */
    public $loadout;

    /**
     * Create a new event instance.
     *
     * @param  Loadout  $loadout
     */
    public function __construct(Loadout $loadout)
    {
        $this->loadout = $loadout;
    }
}
