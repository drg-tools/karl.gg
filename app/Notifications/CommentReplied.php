<?php

namespace App\Notifications;

class CommentReplied extends CommentNotification
{
    public function type(): NotificationType
    {
        return NotificationType::CommentReply;
    }
}
