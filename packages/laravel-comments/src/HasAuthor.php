<?php

namespace Hazzard\Comments;

trait HasAuthor
{
    /**
     * @var string
     */
    protected static $userKey;

    /**
     * @var string
     */
    public static $userModel;

    /**
     * Set the user model and key.
     *
     * @param  string $model
     * @param  string $key
     * @return void
     */
    public static function setUserModel($model, $key)
    {
        static::$userKey = $key;
        static::$userModel = $model;
    }

    /**
     * Get the comment user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo(static::$userModel, 'user_id', static::$userKey);
    }

    /**
     * Eager load the comment users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUser($query)
    {
        return static::$userModel ? $query->with('user') : $query;
    }

    /**
     * @param  string $value
     * @return string
     */
    public function getAuthorNameAttribute($value)
    {
        return $this->userAttribute('name', $value);
    }

    /**
     * @return string
     */
    public function getAuthorEmailAttribute($value)
    {
        return $this->userAttribute('email', $value);
    }

    /**
     * @return string
     */
    public function getAuthorUrlAttribute($value)
    {
        return $this->userAttribute('url', $value);
    }

    /**
     * @return string
     */
    public function getAuthorAvatarAttribute()
    {
        $avatar = $this->userAttribute('avatar', 'gravatar');

        if ($avatar === 'gravatar') {
            $avatar = Gravatar::image($this->author_email);
        }

        return $avatar;
    }

    /**
     * @param  string $key
     * @param  string|null $default
     * @return string|null
     */
    public function userAttribute($key, $default = null)
    {
        if (! $this->user) {
            return $default;
        }

        $attributes = $this->user->authorAttributes();

        return array_key_exists($key, $attributes) ? $attributes[$key] : $default;
    }
}
