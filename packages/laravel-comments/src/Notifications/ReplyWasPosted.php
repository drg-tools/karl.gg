<?php

namespace Hazzard\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReplyWasPosted extends Notification
{
    use Queueable;

    /**
     * @var \Hazzard\Comments\Comment
     */
    public $comment;

    /**
     * Create a new notification instance.
     *
     * @param \Hazzard\Comments\Comment
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('comments::emails.reply_subject', [
                'author' => $this->comment->author_name,
            ]))
            ->line(trans('comments::emails.reply_subject', [
                'author' => $this->comment->author_name,
            ]))
            ->line($this->comment->content)
            ->action(trans('comments::emails.view_action'), $this->comment->permalink);
    }
}
