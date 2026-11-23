<?php

namespace App\Comments;

use App\Models\Comment;
use App\Models\Loadout;
use App\Models\User;
use App\Notifications\BuildCommented;
use App\Notifications\CommentReplied;
use App\Notifications\NotificationType;

/**
 * Tells the people involved in a thread about a new comment. Nobody is told about
 * their own comment, and nobody gets two notifications for the same one.
 */
class CommentNotifier
{
    public function commentPosted(Comment $comment): void
    {
        if (! $comment->approved()) {
            return;
        }

        $loadout = $this->loadoutFor($comment);

        if (! $loadout) {
            return;
        }

        $notifiedIds = array_filter([$comment->user_id]);
        $parentAuthor = $this->parentAuthorOf($comment);

        if ($parentAuthor && ! in_array($parentAuthor->id, $notifiedIds, true) && $parentAuthor->wantsNotification(NotificationType::CommentReply)) {
            $parentAuthor->notify(new CommentReplied($comment, $loadout->id, $loadout->name));
            $notifiedIds[] = $parentAuthor->id;
        }

        $owner = $loadout->creator;

        if ($owner instanceof User && ! in_array($owner->id, $notifiedIds, true)) {
            $owner->notify(new BuildCommented($comment, $loadout->id, $loadout->name));
        }
    }

    /**
     * Query the parent rather than loading the relation, so it is not serialized
     * into the reply's API response along with the parent author's private details.
     */
    protected function parentAuthorOf(Comment $comment): ?User
    {
        return $comment->parent_id ? $comment->parent()->first()?->user : null;
    }

    protected function loadoutFor(Comment $comment): ?Loadout
    {
        $loadoutId = $comment->loadoutId();

        return $loadoutId ? Loadout::with('creator')->find($loadoutId) : null;
    }
}
