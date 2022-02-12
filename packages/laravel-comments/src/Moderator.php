<?php

namespace Hazzard\Comments;

use Hazzard\Comments\Contracts\Akismet;
use Hazzard\Comments\Contracts\Formatter;
use Illuminate\Support\Facades\Auth;

class Moderator implements Contracts\Moderator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Hazzard\Comments\Contracts\Akismet
     */
    protected $akismet;

    /**
     * @var \Hazzard\Comments\Contracts\Formatter
     */
    protected $formatter;

    /**
     * Create a new moderator instance.
     *
     * @param  array  $config
     * @param  \Hazzard\Comments\Contracts\Akismet  $akismet
     * @param  \Hazzard\Comments\Contracts\Formatter  $formatter
     * @return void
     */
    public function __construct(array $config, Akismet $akismet, Formatter $formatter)
    {
        $this->config = $config;
        $this->akismet = $akismet;
        $this->formatter = $formatter;
    }

    /**
     * Determine the comment status.
     *
     * @param  \Hazzard\Comments\Comment  $comment
     * @return string
     */
    public function determineStatus($comment)
    {
        if (Auth::check() && Auth::user()->can('moderate', Comment::class)) {
            return Comment::STATUS_APPROVED;
        }

        if ($this->config['moderation']) {
            return Comment::STATUS_PENDING;
        }

        if ($this->contains($comment, 'blacklist_keys')) {
            return Comment::STATUS_SPAM;
        }

        if ($this->contains($comment, 'moderation_keys')) {
            return Comment::STATUS_PENDING;
        }

        if ($this->hasTooManyLinks($comment)) {
            return Comment::STATUS_PENDING;
        }

        if ($this->isSpam($comment)) {
            return Comment::STATUS_SPAM;
        }

        return Comment::STATUS_APPROVED;
    }

    /**
     * Check if contains specific keys.
     *
     * @param  \Hazzard\Comments\Comment  $comment
     * @param  string  $type
     * @return bool
     */
    protected function contains($comment, $type)
    {
        $fields = $comment->toArray();

        foreach ($this->config[$type] as $key) {
            if (empty($key)) {
                continue;
            }

            foreach ($fields as $field) {
                if (is_string($field) && preg_match('/\b'.$key.'\b/', $field)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the comment content contains too many links.
     *
     * @param  \Hazzard\Comments\Comment  $comment
     * @return bool
     */
    protected function hasTooManyLinks($comment)
    {
        if (! $this->config['max_links']) {
            return false;
        }

        $xml = $this->formatter->parse($comment->content);

        $html = $this->formatter->render($xml);

        $found = preg_match_all('/<a [^>]*href/i', $html);

        return $found >= $this->config['max_links'];
    }

    /**
     * Determine if the if the comment input is spam using Akismet.
     *
     * @param  \Hazzard\Comments\Comment  $comment
     * @return bool
     */
    protected function isSpam($comment)
    {
        if (! $this->config['akismet']) {
            return false;
        }

        return $this->akismet->commentCheck([
            'blog' => url('/'),
            'user_ip' => $comment->author_ip,
            'user_agent' => $comment->user_agent,
            'referrer' => $comment->referrer,
            'permalink' => $comment->permalink,
            'comment_type' => 'comment',
            'comment_author' => $comment->author_name,
            'comment_author_email' => $comment->author_email,
            'comment_author_url' => $comment->author_url,
            'content' => $comment->content,
        ]);
    }
}
