<?php

namespace Hazzard\Comments;

trait HasVotes
{
    /**
     * Get the comment votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(config('comments.models.vote'));
    }

    /**
     * Get the user vote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function userVote()
    {
        return $this->hasOne(Vote::class);
    }

    /**
     * @return string
     */
    public function getVotedAttribute()
    {
        return $this->relationLoaded('userVote') && $this->userVote ? $this->userVote->type : null;
    }

    /**
     * Eager load the vote for the given user id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUserVote($query, $userId)
    {
        return $query->with(['userVote' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);
    }
}
