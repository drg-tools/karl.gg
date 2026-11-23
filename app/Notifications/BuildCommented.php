<?php

namespace App\Notifications;

class BuildCommented extends CommentNotification
{
    public function type(): NotificationType
    {
        return NotificationType::BuildComment;
    }
}
