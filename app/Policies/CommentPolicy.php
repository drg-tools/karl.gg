<?php

namespace App\Policies;

use App\Comment;
use App\User;

class CommentPolicy
{
    public function moderate(User $user): bool
    {
        return $user->can('manage-comments');
    }

    /**
     * Authors may edit their approved comments within the edit window.
     */
    public function update(User $user, Comment $comment): bool
    {
        if ($this->moderate($user)) {
            return true;
        }

        return $comment->approved() && $this->isRecentCommentBy($user, $comment);
    }

    /**
     * Authors may delete their approved or pending comments within the edit window.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($this->moderate($user)) {
            return true;
        }

        return ($comment->approved() || $comment->pending()) && $this->isRecentCommentBy($user, $comment);
    }

    public function report(User $user, Comment $comment): bool
    {
        return $comment->approved();
    }

    protected function isRecentCommentBy(User $user, Comment $comment): bool
    {
        return $user->getKey() === $comment->user_id
            && $comment->created_at->getTimestamp() > now()->getTimestamp() - config('comments.edit_window');
    }
}
