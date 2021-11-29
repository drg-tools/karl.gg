<?php

namespace Hazzard\Comments;

use Illuminate\Contracts\Auth\Authenticatable as User;

class CommentPolicy
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = config('comments');
    }

    /**
     * Determine if the user can moderate comments.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return bool
     */
    public function moderate(User $user)
    {
        return $user->can('moderate-comments');
    }

    /**
     * Determine if the user can edit the given comment.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  \Hazzard\Comments\Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        if ($user->can('moderate', Comment::class)) {
            return true;
        }

        if (! $comment->approved()) {
            return false;
        }

        if ((int) $user->getKey() !== $comment->user_id) {
            return false;
        }

        if ($seconds = $this->config['allow_edit']) {
            return $comment->created_at->getTimestamp() > time() - $seconds;
        }

        return false;
    }

    /**
     * Determine if the user can delete the given comment.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  \Hazzard\Comments\Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment)
    {
        if ($user->can('moderate', Comment::class)) {
            return true;
        }

        if (! $comment->approved() && ! $comment->pending()) {
            return false;
        }

        if ((int) $user->getKey() !== $comment->user_id) {
            return false;
        }

        if ($seconds = $this->config['allow_delete']) {
            return $comment->created_at->getTimestamp() > time() - $seconds;
        }

        return false;
    }

    /**
     * Determine if the user can report the given comment.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  \Hazzard\Comments\Comment $comment
     * @return bool
     */
    public function report(User $user, Comment $comment)
    {
        if (! $comment->approved()) {
            return false;
        }

        return $this->config['allow_reports'];
    }
}
