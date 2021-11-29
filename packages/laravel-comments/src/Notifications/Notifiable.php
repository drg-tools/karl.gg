<?php

namespace Hazzard\Comments\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    /**
     * @var string
     */
    protected $email;

    /**
     * Create a new instance.
     *
     * @param string $email
     * @param void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return 1;
    }
}
