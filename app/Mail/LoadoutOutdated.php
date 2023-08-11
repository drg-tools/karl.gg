<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoadoutOutdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $loadouts;

    /**
     * @var string
     */
    public $unsubscribeLink;

    /**
     * Create a new message instance.
     *
     * @param  array  $loadouts
     * @param  $unsubcribeLink
     */
    public function __construct($loadouts, $unsubscribeLink)
    {
        $this->loadouts = $loadouts;
        $this->unsubscribeLink = $unsubscribeLink;
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
