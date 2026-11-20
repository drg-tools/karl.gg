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
     * @param  array{page?: int, order?: ?string, target?: ?int, user_id?: ?int}  $args
     */
    public function forPage(string $pageId, array $args = []): Paginator
    {
        $perPage = config('comments.per_page');
        $userId = $args['user_id'] ?? null;
        [$column, $direction] = $this->orderFields($args['order'] ?? null);
        $visible = Comment::query()->where('page_id', $pageId);
        $this->visibleTo($visible, $userId);

        $query = (clone $visible)->whereNull('parent_id');
        $total = $query->count('id');
        $count = $total + (clone $visible)
            ->whereNotNull('parent_id')
            ->whereIn('root_id', (clone $query)->select('id'))
            ->count('id');

        $page = $args['page'] ?? 1;

        if (! empty($args['target'])) {
            $page = $this->pageNumberFor($visible, $query, $args['target'], $column, $direction, $perPage) ?? $page;
        }

        $page = min(max(1, $page), max(1, (int) ceil($total / $perPage)));

        $comments = $query->with('user')
            ->when($userId, fn (Builder $query) => $query->withUserVote($userId))
            ->with(['replies' => function (HasMany $query) use ($column, $direction, $pageId, $userId): void {
                $query->where('page_id', $pageId)->whereNotNull('parent_id')
                    ->orderBy($column, $direction)->orderBy('id', $direction)->with('user');

                if ($userId) {
                    $query->withUserVote($userId);
                }

                $this->visibleTo($query->getQuery(), $userId);
            }])
            ->orderBy($column, $direction)
            ->orderBy('id', $direction)
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
     * Count the comments that are not yet approved for a signed-in author.
     */
    public function unapprovedCount(int $userId): int
    {
        return Comment::query()
            ->where('user_id', $userId)
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

    protected function pageNumberFor(Builder $visible, Builder $threads, int $commentId, string $column, string $direction, int $perPage): ?int
    {
        $comment = (clone $visible)->find($commentId);

        if (! $comment) {
            return null;
        }

        $root = $comment->parent_id === null ? $comment : (clone $threads)->find($comment->root_id);

        if (! $root) {
            return null;
        }

        $value = $root->getRawOriginal($column);
        $operator = $direction === 'desc' ? '>' : '<';
        $before = (clone $threads)->where(function (Builder $query) use ($column, $operator, $value, $root): void {
            $query->where($column, $operator, $value)
                ->orWhere(fn (Builder $query) => $query->where($column, $value)->where('id', $operator, $root->id));
        })->count('id');

        return intdiv($before, $perPage) + 1;
    }

    /**
     * Hide trashed comments, and unapproved ones unless the viewer wrote them.
     */
    protected function visibleTo(Builder $query, ?int $userId): void
    {
        $query->where('status', '!=', Comment::STATUS_TRASH)
            ->where(function (Builder $query) use ($userId): void {
                $query->where('status', Comment::STATUS_APPROVED);

                if ($userId) {
                    $query->orWhere('user_id', $userId);
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
}
