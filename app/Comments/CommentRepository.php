<?php

namespace App\Comments;

use App\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    public function __construct(protected Formatter $formatter) {}

    /**
     * Get one page of approved threads for a page, plus the viewer's own pending comments.
     *
     * @param  array{page?: int, order?: ?string, target?: ?int, user_id?: ?int, email?: ?string}  $args
     */
    public function forPage(string $pageId, array $args = []): Paginator
    {
        $perPage = config('comments.per_page');
        $userId = $args['user_id'] ?? null;
        $viewer = $userId ?: ($args['email'] ?? null);
        [$column, $direction] = $this->orderFields($args['order'] ?? null);

        $page = (int) ($args['page'] ?? 1) ?: 1;

        if (! empty($args['target'])) {
            $page = $this->pageNumberFor((int) $args['target']) ?? $page;
        }

        $query = Comment::query()->where('page_id', $pageId);
        $this->visibleTo($query, $viewer);

        $count = $query->count('id');
        $query->whereNull('parent_id');
        $total = $query->count('id');

        $comments = $query->with('user')
            ->when($userId, fn (Builder $query) => $query->withUserVote($userId))
            ->with(['replies' => function (HasMany $query) use ($column, $direction, $viewer, $userId): void {
                $query->orderBy($column, $direction)->with('user');

                if ($userId) {
                    $query->withUserVote($userId);
                }

                $this->visibleTo($query->getQuery(), $viewer);
            }])
            ->orderBy($column, $direction)
            ->forPage($page, $perPage)
            ->get()
            ->makeHidden(['author_email', 'user_agent', 'author_ip']);

        $comments->each(fn (Comment $comment) => $comment->replies->makeHidden(['author_email', 'user_agent', 'author_ip']));

        return new Paginator($comments, $total, $perPage, $page, ['count' => $count]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Comment
    {
        $comment = new Comment($attributes);
        $comment->status = $this->determineStatus($comment);
        $comment->save();

        return $comment->fresh();
    }

    public function updateContent(Comment $comment, string $content): Comment
    {
        $comment->content = $content;
        $comment->status = $this->determineStatus($comment);
        $comment->save();

        return $comment;
    }

    public function upvote(Comment $comment, int $userId): void
    {
        $this->saveVote($comment, $userId, Vote::UP);
    }

    public function downvote(Comment $comment, int $userId): void
    {
        $this->saveVote($comment, $userId, Vote::DOWN);
    }

    public function removeVote(Comment $comment, int $userId): void
    {
        $vote = $comment->votes()->where('user_id', $userId)->first();

        if (! $vote) {
            return;
        }

        DB::transaction(function () use ($comment, $vote): void {
            $comment->decrement($vote->type === Vote::UP ? 'upvotes' : 'downvotes');
            $vote->delete();
        });
    }

    /**
     * Record a report, holding the comment for moderation once it has enough of them.
     */
    public function report(Comment $comment, int $userId): void
    {
        if ($comment->reports()->where('user_id', $userId)->exists()) {
            return;
        }

        $comment->reports()->create(['user_id' => $userId]);

        if ($comment->reports()->count() >= config('comments.max_reports')) {
            $comment->update(['status' => Comment::STATUS_PENDING]);
        }
    }

    /**
     * Count the comments by a user id or guest email that are not yet approved.
     */
    public function unapprovedCount(int|string $userIdOrEmail): int
    {
        return Comment::query()
            ->where($this->authorColumn($userIdOrEmail), $userIdOrEmail)
            ->where('status', '!=', Comment::STATUS_APPROVED)
            ->count('id');
    }

    /**
     * Moderators are trusted; link-heavy comments wait for review.
     */
    protected function determineStatus(Comment $comment): string
    {
        if (Auth::user()?->can('moderate', Comment::class)) {
            return Comment::STATUS_APPROVED;
        }

        $links = preg_match_all('/<a [^>]*href/i', $comment->content_html);

        return $links >= config('comments.max_links') ? Comment::STATUS_PENDING : Comment::STATUS_APPROVED;
    }

    protected function saveVote(Comment $comment, int $userId, string $type): void
    {
        $vote = $comment->votes()->where('user_id', $userId)->first();

        if ($vote?->type === $type) {
            return;
        }

        DB::transaction(function () use ($comment, $userId, $type, $vote): void {
            if ($vote) {
                $comment->decrement($vote->type === Vote::UP ? 'upvotes' : 'downvotes');
                $vote->update(['type' => $type]);
            } else {
                $comment->votes()->create(['type' => $type, 'user_id' => $userId]);
            }

            $comment->increment($type === Vote::UP ? 'upvotes' : 'downvotes');
        });
    }

    /**
     * Find which page a deep-linked comment's thread sits on, using the default order.
     */
    protected function pageNumberFor(int $commentId): ?int
    {
        $comment = Comment::query()->find($commentId);

        if (! $comment) {
            return null;
        }

        [$column, $direction] = $this->orderFields(null);
        $value = $comment->root_id ? $comment->root()->value($column) : $comment->getRawOriginal($column);

        $before = Comment::query()
            ->where('page_id', $comment->page_id)
            ->whereNull('root_id')
            ->where($column, $direction === 'desc' ? '>' : '<', $value)
            ->count('id');

        return intdiv($before, config('comments.per_page')) + 1;
    }

    /**
     * Hide trashed comments, and unapproved ones unless the viewer wrote them.
     */
    protected function visibleTo(Builder $query, int|string|null $viewer): void
    {
        $query->where('status', '!=', Comment::STATUS_TRASH)
            ->where(function (Builder $query) use ($viewer): void {
                $query->where('status', Comment::STATUS_APPROVED);

                if ($viewer) {
                    $query->orWhere($this->authorColumn($viewer), $viewer);
                }
            });
    }

    /**
     * @return array{0: string, 1: 'asc'|'desc'}
     */
    protected function orderFields(?string $order): array
    {
        return match ($order) {
            Comment::ORDER_LATEST => ['created_at', 'desc'],
            Comment::ORDER_OLDEST => ['created_at', 'asc'],
            default => ['upvotes', 'desc'],
        };
    }

    protected function authorColumn(int|string $userIdOrEmail): string
    {
        return is_string($userIdOrEmail) && str_contains($userIdOrEmail, '@') ? 'author_email' : 'user_id';
    }
}
