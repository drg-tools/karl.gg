<?php

namespace Hazzard\Comments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hazzard\Comments\Moderator
 */
class Moderator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'comments.moderator';
    }
}
