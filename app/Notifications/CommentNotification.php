<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * An in-app notification about a new comment. The comment is copied into the
 * notification so it still reads correctly after the comment is edited or deleted.
 */
abstract class CommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment, public int $loadoutId, public string $loadoutName) {}

    abstract public function type(): NotificationType;

    /**
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return $notifiable->wantsNotification($this->type()) ? ['database'] : [];
    }

    public function databaseType(User $notifiable): string
    {
        return $this->type()->value;
    }

    /**
     * @return array{comment_id: int, loadout_id: int, loadout_name: string, author_name: string, excerpt: string, url: string}
     */
    public function toArray(User $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'loadout_id' => $this->loadoutId,
            'loadout_name' => $this->loadoutName,
            'author_name' => $this->comment->author_name ?: 'Anonymous',
            'excerpt' => Str::limit(trim(html_entity_decode(strip_tags($this->comment->content_html))), 140),
            'url' => route('preview.show', $this->loadoutId).'#!comment='.$this->comment->id,
        ];
    }
}
