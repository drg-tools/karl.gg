<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoadoutOutdated extends Mailable
{
    use Queueable, SerializesModels;

    public $loadouts;

    /**
     * Create a new message instance.
     *
     * @param  array  $loadouts
     */
    public function __construct($loadouts)
    {
        $this->loadouts = $loadouts;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.loadout.outdated')
            ->subject('Loadouts Outdated - Karl.gg');
    }
}
