<?php

namespace App;

use App\Comments\Formatter;
use App\Comments\Report;
use App\Comments\Vote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Gate;

class Comment extends Model
{
    use HasFactory;

    const STATUS_SPAM = 'spam';

    const STATUS_TRASH = 'trash';

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const ORDER_BEST = 'best';

    const ORDER_LATEST = 'latest';

    const ORDER_OLDEST = 'oldest';

    /**
     * @var list<string>
     */
    const STATUSES = [self::STATUS_APPROVED, self::STATUS_PENDING, self::STATUS_SPAM, self::STATUS_TRASH];

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * @var list<string>
     */
    protected $appends = [
        'url',
        'voted',
        'content_html',
        'author_avatar',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'upvotes' => 'integer',
            'downvotes' => 'integer',
            'root_id' => 'integer',
            'parent_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Every reply in the thread, keyed by its top-level comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'root_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(static::class, 'id', 'parent_id');
    }

    public function root(): HasOne
    {
        return $this->hasOne(static::class, 'id', 'root_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function userVote(): HasOne
    {
        return $this->hasOne(Vote::class);
    }

    /**
     * Eager load the given user's vote on each comment.
     */
    public function scopeWithUserVote(Builder $query, int $userId): Builder
    {
        return $query->with(['userVote' => fn (HasOne $query) => $query->where('user_id', $userId)]);
    }

    /**
     * Comments are stored as s9e/TextFormatter XML.
     */
    public function setContentAttribute(?string $value): void
    {
        $this->attributes['content'] = app(Formatter::class)->parse($value ?? '');
    }

    public function getContentAttribute(?string $value): string
    {
        return app(Formatter::class)->unparse($value ?? '<t></t>');
    }

    public function getContentHtmlAttribute(): string
    {
        return app(Formatter::class)->render($this->attributes['content'] ?? '<t></t>');
    }

    public function getUrlAttribute(): string
    {
        return $this->permalink.'#!comment='.$this->getKey();
    }

    public function getVotedAttribute(): ?string
    {
        return $this->relationLoaded('userVote') ? $this->userVote?->type : null;
    }

    public function getAuthorNameAttribute(?string $value): ?string
    {
        return $this->user ? $this->user->name : $value;
    }

    public function getAuthorEmailAttribute(?string $value): ?string
    {
        return $this->user ? $this->user->email : $value;
    }

    public function getAuthorUrlAttribute(?string $value): ?string
    {
        return $this->user ? route('user.profile', $this->user) : $value;
    }

    public function getAuthorAvatarAttribute(): string
    {
        $hash = md5(strtolower(trim((string) $this->author_email)));

        return "https://www.gravatar.com/avatar/{$hash}/?d=monsterid&s=200&r=g";
    }

    public function approved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function pending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function spam(): bool
    {
        return $this->status === self::STATUS_SPAM;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $attributes = parent::toArray();

        unset($attributes['user'], $attributes['user_vote']);

        if ($this->relationLoaded('parent')) {
            $attributes['parent'] = $this->parent?->toArray();
        }

        return array_merge($attributes, [
            'can_update' => Gate::allows('update', $this),
            'can_delete' => Gate::allows('delete', $this),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'replies' => $attributes['replies'] ?? [],
        ]);
    }
}
