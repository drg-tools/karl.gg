<?php

namespace App\Notifications;

/**
 * The kinds of notification a user can turn on or off from their settings.
 */
enum NotificationType: string
{
    case BuildComment = 'build_comment';
    case CommentReply = 'comment_reply';

    public function label(): string
    {
        return match ($this) {
            self::BuildComment => 'Comments on my builds',
            self::CommentReply => 'Replies to my comments',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::BuildComment => 'When someone comments on a build you published.',
            self::CommentReply => 'When someone replies directly to one of your comments.',
        };
    }

    /**
     * Whether users who have never changed this setting receive it.
     */
    public function enabledByDefault(): bool
    {
        return true;
    }
}
