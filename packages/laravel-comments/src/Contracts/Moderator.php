<?php

namespace Hazzard\Comments\Contracts;

interface Moderator
{
    /**
     * Determine the comment status.
     *
     * @param  \Hazzard\Comments\Comment $comment
     * @return string
     */
    public function determineStatus($comment);
}
