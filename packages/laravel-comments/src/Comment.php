<?php

namespace Hazzard\Comments;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasVotes;
    use HasAuthor;

    const STATUS_SPAM = 'spam';
    const STATUS_TRASH = 'trash';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    const ORDER_BEST = 'best';
    const ORDER_LATEST = 'latest';
    const ORDER_OLDEST = 'oldest';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'root_id' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'voted',
        'content_html',
        'author_avatar',
    ];

    /**
     * Create a new model instance.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('comments.table_names.comments'));

        parent::__construct($attributes);
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the comment replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(static::class, 'root_id');
    }

    /**
     * Get the parent comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function parent()
    {
        return $this->hasOne(static::class, 'id', 'parent_id');
    }

    /**
     * Get the root comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function root()
    {
        return $this->hasOne(static::class, 'id', 'root_id');
    }

    /**
     * Get the comment reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(config('comments.models.report'));
    }

    /**
     * Parse the content before it's saved.
     *
     * @param  string $value
     * @return void
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $this->getFormatter()->parse($value);
    }

    /**
     * Unparse the parsed content.
     *
     * @param  string $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        return $this->getFormatter()->unparse($value);
    }

    /**
     * Get the content rendered as HTML.
     *
     * @return string
     */
    public function getContentHtmlAttribute()
    {
        return $this->getFormatter()->render($this->attributes['content']);
    }
    /**
     * Get the comment url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->permalink.'#!comment='.$this->getKey();
    }

    /**
     * Get the admin edit url.
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return route('comments.dashboard').'#!comment='.$this->getKey();
    }

    /**
     * @return bool
     */
    public function approved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * @return bool
     */
    public function pending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function spam()
    {
        return $this->status === self::STATUS_SPAM;
    }

    /**
     * @return \Hazzard\Comments\Contracts\Formatter
     */
    protected function getFormatter()
    {
        return app('comments.formatter');
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        unset($attributes['user'], $attributes['user_votes']);

        if ($this->relationLoaded('parent')) {
            $attributes['parent'] = $this->parent ? $this->parent->toArray() : null;
        }

        return array_merge($attributes, [
            'can_update' => Gate::allows('update', $this),
            'can_delete' => Gate::allows('delete', $this),
            'edit_url' => Gate::allows('moderate', static::class) ? $this->edit_url : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
            'replies' => $this->relationLoaded('replies') ? $this->replies->toArray() : [],
        ]);
    }
}
