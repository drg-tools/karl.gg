<?php

namespace Hazzard\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentWasPosted extends Notification
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
            ->subject(trans('comments::emails.comment_subject', [
                'author' => $this->comment->author_name,
            ]))
            ->line(trans('comments::emails.comment_author', [
                'name' => $this->comment->author_name,
                'email' => $this->comment->author_email,
            ]))
            ->line(trans('comments::emails.comment_status', [
                'status' => $this->comment->status,
            ]))
            ->line(trans('comments::emails.comment_content', [
                'content' => $this->comment->content,
            ]))
            ->line(trans('comments::emails.comment_edit_url', [
                'url' => $this->comment->edit_url,
            ]))
            ->action(trans('comments::emails.view_action'), $this->comment->permalink);
    }
}
