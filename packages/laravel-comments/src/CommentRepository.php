<?php

namespace Hazzard\Comments;

use Hazzard\Comments\Contracts\Moderator;
use Hazzard\Comments\Facades\Formatter as FormatterFacade;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CommentRepository implements Contracts\CommentRepository
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Hazzard\Comments\Contracts\Moderator
     */
    protected $moderator;

    /**
     * Create a new instance.
     *
     * @param  array  $config
     * @param  \Hazzard\Comments\Contracts\Moderator  $moderator
     * @return void
     */
    public function __construct(array $config, Moderator $moderator)
    {
        $this->config = $config;
        $this->moderator = $moderator;
    }

    /**
     * Get comments.
     *
     * @param  array  $args
     * @return \Hazzard\Comments\Paginator
     */
    public function get(array $args)
    {
        $page = Arr::get($args, 'page') ?: 1;
        $pageId = Arr::get($args, 'page_id');
        $target = Arr::get($args, 'target');
        $order = Arr::get($args, 'order');
        $userId = Arr::get($args, 'user_id');
        $email = Arr::get($args, 'email');
        $perPage = $this->config['per_page'];
        $idOrEmail = $userId ?: $email;
        $hideUserDetails = Arr::get($args, 'hide_user_details', false);
        $cId = Arr::get($args, 'commentable_id');
        $cType = str_replace('.', '\\', Arr::get($args, 'commentable_type'));

        // If a target comment exists get the page number for that comment.
        if ($target && $perPage && $pg = $this->getPageNumber($target)) {
            $page = $pg;
        }

        $query = $this->query();

        if ($pageId) {
            $query->where('page_id', $pageId);
        } else {
            $query->where('commentable_id', $cId)
                ->where('commentable_type', $cType);
        }

        $this->applyStatusScope($query, Comment::STATUS_APPROVED, $idOrEmail);

        $count = $query->count('id');

        $query->whereNull('parent_id');

        $total = $query->count('id');

        $query->withUser();

        // Load user vote.
        if ($userId && $this->config['allow_votes']) {
            $query->withUserVote($userId);
        }

        // Paginate.
        if ($perPage) {
            $query->forPage($page, $perPage);
        }

        // Order by column and direction.
        [$column, $direction] = $this->getOrderFields($order);
        $query->orderBy($column, $direction);

        // Load replies.
        if ($this->config['allow_replies']) {
            $query->with(['replies' => function ($query) use ($column, $direction, $idOrEmail, $userId) {
                $query->orderBy($column, $direction)
                    ->withUser();

                if ($userId && $this->config['allow_votes']) {
                    $query->withUserVote($userId);
                }

                $this->applyStatusScope($query, Comment::STATUS_APPROVED, $idOrEmail);
            }]);
        }

        $comments = $query->get();

        if ($hideUserDetails) {
            $method = method_exists($comments, 'makeHidden') ? 'makeHidden' : 'withHidden';
            $comments->$method(['user', 'author_email', 'user_agent', 'author_ip']);
        }

        return new Paginator($comments, $total, $perPage ?: 99999, $page, compact('count'));
    }

    /**
     * Get comments for admin.
     *
     * @param  array  $args
     * @return \Hazzard\Comments\Paginator
     */
    public function getForAdmin(array $args)
    {
        $order = Arr::get($args, 'order');
        $status = Arr::get($args, 'status');
        $pageId = Arr::get($args, 'page_id');
        $perPage = Arr::get($args, 'per_page') ?: 15;
        $cId = Arr::get($args, 'commentable_id');
        $cType = str_replace('.', '\\', Arr::get($args, 'commentable_type'));

        $query = $this->query()
            ->withUser()
            ->with('parent.user')
            ->orderBy('created_at', $order === 'latest' ? 'DESC' : 'ASC');

        if ($pageId) {
            $query->where('page_id', $pageId);
        } elseif ($cId && $cType) {
            $query->where(function ($query) use ($cId, $cType) {
                $query->where('commentable_id', $cId)
                ->where('commentable_type', $cType);
            });
        }

        if ($status === 'all') {
            $query->where(function ($query) {
                $query->where('status', Comment::STATUS_APPROVED)
                ->orWhere('status', Comment::STATUS_PENDING);
            });
        } else {
            $query->where('status', $status);
        }

        $pluckMethod = method_exists($query, 'lists') ? 'lists' : 'pluck';

        $page = Paginator::resolveCurrentPage();
        $total = $query->getQuery()->getCountForPagination();
        $comments = $query->forPage($page, $perPage)->get();

        $pageCount = $this->query()
            ->select('page_id')
            ->selectRaw('COUNT(id) as aggregate')
            ->whereNotNull('page_id')
            ->groupBy('page_id')
            ->getQuery()
            ->pluck('aggregate', 'page_id');

        $commentableCount = $this->query()
            ->select('commentable_id', 'commentable_type')
            ->selectRaw('COUNT(id) as aggregate')
            ->whereNotNull('commentable_id')
            ->groupBy('commentable_id', 'commentable_type')
            ->getQuery()
            ->get();

        if (is_array($commentableCount)) {
            $commentableCount = collect($commentableCount);
        }

        $commentableCount = $commentableCount->each(function ($comment) {
            $comment->commentable = $comment->commentable_id.'|'.$comment->commentable_type;
        })
        ->$pluckMethod('aggregate', 'commentable');

        $query = $this->query()
            ->select('status')
            ->selectRaw('COUNT(id) as aggregate')
            ->groupBy('status');

        if ($pageId) {
            $query->where('page_id', $pageId);
        }

        if ($cId && $cType) {
            $query->where('commentable_id', $cId)
                ->where('commentable_type', $cType);
        }

        $statusCount = $query->getQuery()->$pluckMethod('aggregate', 'status');

        if (is_array($statusCount)) {
            $statusCount = collect($statusCount);
        }

        return new Paginator($comments, $total, $perPage, $page, [
            'pageCount' => $pageCount,
            'commentableCount' => $commentableCount->map(function ($count) {
                return (int) $count;
            }),
            'statusCount' => $statusCount->map(function ($count) {
                return (int) $count;
            }),
        ]);
    }

    /**
     * Create a new comment.
     *
     * @param  array  $args
     * @return \Hazzard\Comments\Comment
     */
    public function create(array $args)
    {
        if (isset($args['commentable_type'])) {
            $args['commentable_type'] = str_replace('.', '\\', $args['commentable_type']);
        }

        $comment = $this->model()->fill($args);
        $comment->status = $this->moderator->determineStatus($comment);

        unset($comment['referrer']);

        $comment->save();
        $comment = $comment->fresh();

        return $comment;
    }

    /**
     * Update an existing comment.
     *
     * @param  int  $id
     * @param  array  $args
     * @return \Hazzard\Comments\Comment
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $args)
    {
        foreach ($args as $key => $value) {
            if (is_null($value)) {
                unset($args[$key]);
            }
        }

        $comment = $this->findOrFail($id)->fill($args);

        if (! isset($args['status'])) {
            $comment->status = $this->moderator->determineStatus($comment);
        }

        unset($comment['referrer']);
        $comment->save();

        return $comment;
    }

    /**
     * Upvote the given comment.
     *
     * @param  int  $commentId
     * @param  int  $userId
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function upvote($commentId, $userId)
    {
        return $this->saveVote($commentId, $userId, Vote::UP);
    }

    /**
     * Downvote the given comment.
     *
     * @param  int  $commentId
     * @param  int  $userId
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function downvote($commentId, $userId)
    {
        return $this->saveVote($commentId, $userId, Vote::DOWN);
    }

    /**
     * @param  int  $commentId
     * @param  int  $userId
     * @param  string  $type
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function saveVote($commentId, $userId, $type)
    {
        $comment = $this->findOrFail($commentId);

        $vote = $comment->votes()->where('user_id', $userId)->first();

        if ($vote) {
            if ($type === Vote::UP && $vote->type === Vote::DOWN) {
                DB::transaction(function () use ($comment) {
                    $comment->increment('upvotes');
                    $comment->decrement('downvotes');
                });
            } elseif ($type === Vote::DOWN && $vote->type === Vote::UP) {
                DB::transaction(function () use ($comment) {
                    $comment->increment('downvotes');
                    $comment->decrement('upvotes');
                });
            }

            $vote->update(compact('type'));
        } else {
            DB::transaction(function () use ($comment, $userId, $type) {
                $comment->votes()->save(new Vote([
                    'type' => $type,
                    'user_id' => $userId,
                ]));

                $comment->increment($type === Vote::UP ? 'upvotes' : 'downvotes');
            });
        }

        return true;
    }

    /**
     * Remove the vote for the given comment.
     *
     * @param  int  $commentId
     * @param  int  $userId
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function removeVote($commentId, $userId)
    {
        $comment = $this->findOrFail($commentId);

        $vote = $comment->votes()->where('user_id', $userId)->first();

        if (! $vote) {
            return false;
        }

        if ($vote->type === Vote::UP) {
            $comment->decrement('upvotes');
        } else {
            $comment->decrement('downvotes');
        }

        $vote->delete();

        return true;
    }

    /**
     * Report a comment.
     *
     * @param  \Hazzard\Comments\Comment  $comment
     * @param  int  $userId  The user that reports it.
     * @return bool
     */
    public function report($comment, $userId)
    {
        if ($comment->reports()->where('user_id', $userId)->first()) {
            return false;
        }

        $comment->reports()->save(new Report([
            'user_id' => $userId,
        ]));

        $status = $this->config['report_status'];
        $maxReports = $this->config['max_reports'];

        if ($maxReports && $comment->reports()->count() >= $maxReports) {
            $comment->update(['status' => $status]);
        }

        return true;
    }

    /**
     * Delete the given comments.
     *
     * @param  array  $ids
     * @return int
     */
    public function deleteByIds(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->delete();
    }

    /**
     * Update the status of the given comments.
     *
     * @param  array  $ids
     * @param  string  $status
     * @return int
     */
    public function updateStatusByIds(array $ids, $status)
    {
        return $this->query()->whereIn('id', $ids)->update(compact('status'));
    }

    /**
     * Get the number of unapproved comments for a user.
     *
     * @param  int|string  $idOrEmail
     * @return int
     */
    public function getUnapprovedCount($idOrEmail)
    {
        return $this->query()
            ->where($this->userColumnValue($idOrEmail))
            ->where('status', '!=', Comment::STATUS_APPROVED)
            ->count('id');
    }

    /**
     * Determine if the comment content has already been posted.
     *
     * @param  args  $input  [content, user, page_id, commentable_id, commentable_type]
     * @return bool
     */
    public function hasDuplicateContent(array $args)
    {
        $query = $this->query()
            ->where('content', FormatterFacade::parse($args['content']))
            ->where($this->userColumnValue($args['user']));

        if (isset($args['page_id'])) {
            $query->where('page_id', $args['page_id']);
        } else {
            $cId = Arr::get($args, 'commentable_id');
            $cType = str_replace('.', '\\', Arr::get($args, 'commentable_type'));

            $query->where('commentable_id', $cId)
                ->where('commentable_type', $cType);
        }

        return ! is_null($query->value('id'));
    }

    /**
     * Find a comment by its primary key.
     *
     * @param  int  $id
     * @return \Hazzard\Comments\Comment|null
     */
    public function find($id)
    {
        return $this->query()->find($id);
    }

    /**
     * Find a comment by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @return \Hazzard\Comments\Comment
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id)
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Get page number by comment id.
     *
     * @param  int  $commentId
     * @return int|null
     */
    protected function getPageNumber($commentId)
    {
        [$column, $direction] = $this->getOrderFields(null);

        $operator = $direction === 'DESC' ? '>' : '<';

        $perPage = $this->config['per_page'];

        if (! $comment = $this->find($commentId)) {
            return;
        }

        if ($comment->root_id) {
            $value = $comment->root()->value($column);
        } else {
            $value = $comment->{$column};
        }

        if ($value) {
            $count = $this->query()
                ->where($column, $operator, $comment->fromDateTime($value))
                ->whereNull('root_id')
                ->count('id');

            $page = intval(floor($count / $perPage)) + 1;

            if ($page > 1) {
                return $page;
            }
        }
    }

    /**
     * Apply where status != trash and (status or (user_id | author_email)).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @param  int|string  $idOrEmail
     * @return void
     */
    protected function applyStatusScope($query, $status, $idOrEmail)
    {
        $query->where('status', '!=', Comment::STATUS_TRASH);

        $query->where(function ($query) use ($status, $idOrEmail) {
            $query->where('status', $status);

            if ($idOrEmail) {
                $query->orWhere($this->userColumnValue($idOrEmail));
            }
        });
    }

    /**
     * Get the column and direction for the "order by" clause.
     *
     * @param  int  $type
     * @return array
     */
    protected function getOrderFields($type)
    {
        $order = [
            Comment::ORDER_BEST => ['upvotes', 'DESC'],
            Comment::ORDER_LATEST => ['created_at', 'DESC'],
            Comment::ORDER_OLDEST => ['created_at', 'ASC'],
        ];

        if (isset($order[$type])) {
            return $order[$type];
        }

        return $this->getOrderFields($this->config['default_order']);
    }

    /**
     * @param  int|string  $value
     * @return array
     */
    protected function userColumnValue($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return ['author_email' => $value];
        }

        return ['user_id' => $value];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        return $this->model()->newQuery();
    }

    /**
     * @return \Hazzard\Comments\Comment
     */
    protected function model()
    {
        return new $this->config['models']['comment'];
    }
}
