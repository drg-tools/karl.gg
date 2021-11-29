<?php

namespace Hazzard\Comments\Contracts;

interface Akismet
{
    /**
     * Check comment for spam.
     *
     * @link https://akismet.com/development/api/#comment-check.
     *
     * @param  array $params
     * @return boolean
     */
    public function commentCheck(array $params): bool;
}
