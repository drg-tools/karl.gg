<?php

namespace Hazzard\Comments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hazzard\Comments\Formatter
 */
class Formatter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'comments.formatter';
    }
}
