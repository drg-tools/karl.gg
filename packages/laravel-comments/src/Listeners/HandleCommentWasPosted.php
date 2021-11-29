<?php

namespace Hazzard\Comments\Listeners;

use Hazzard\Comments\Notifications;
use Hazzard\Comments\Events\CommentWasPosted;
use Hazzard\Comments\Notifications\Notifiable;
use Hazzard\Comments\Events\BroadcastCommentWasPosted;

class HandleCommentWasPosted
{
    /**
     * Handle the event.
     *
     * @param  \Hazzard\Comments\Events\CommentWasPosted $event
     * @return void
     */
    public function handle(CommentWasPosted $event)
    {
        $comment = $event->comment;

        if (config('comments.broadcast') && $comment->approved()) {
            event(new BroadcastCommentWasPosted($comment));
        }

        if ($email = config('comments.admin_notification')) {
            $this->adminNotification($comment, $email);
        }

        if (config('comments.reply_notification')) {
            $this->replyNotification($comment);
        }
    }

    /**
     * @param  \Hazzard\Comments\Comment $comment
     * @return void
     */
    protected function replyNotification($comment)
    {
        if (! $comment->approved() || ! $comment->parent) {
            return;
        }

        $email = $comment->parent->author_email;
        $adminEmail = config('comments.admin_notification');

        if (! $email || $email === $adminEmail || $email === $comment->author_email) {
            return;
        }

        (new Notifiable($email))->notify(new Notifications\ReplyWasPosted($comment));
    }

    /**
     * @param  \Hazzard\Comments\Comment $comment
     * @param  string $email
     * @return void
     */
    protected function adminNotification($comment, $email)
    {
        if ($comment->user && $email === $comment->userAttribute('email') || $comment->spam()) {
            return;
        }

        (new Notifiable($email))->notify(new Notifications\CommentWasPosted($comment));
    }
}
