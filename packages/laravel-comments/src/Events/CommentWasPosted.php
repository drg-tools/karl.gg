<?php

namespace Hazzard\Comments\Events;

class CommentWasPosted
{
    /**
     * @var \Hazzard\Comments\Comment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param  \Hazzard\Comments\Comment $comment
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }
}
