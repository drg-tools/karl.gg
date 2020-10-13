<?php

namespace App\Events;

use App\Loadout;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
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
