<?php

namespace Hazzard\Comments;

trait HasComments
{
    /**
     * Get all of the user's comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(config('comments.comment_model'));
    }
}
