<?php

namespace Hazzard\Comments;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    const UP = 'up';
    const DOWN = 'down';

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
        'comment_id' => 'integer',
    ];

    /**
     * Create a new model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('comments.table_names.votes'));

        parent::__construct($attributes);
    }

    /**
     * Get the vote comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo(config('comments.models.comment'));
    }
}
