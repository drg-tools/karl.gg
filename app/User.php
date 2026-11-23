<?php

namespace App;

use App\Notifications\NotificationType;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Nagy\LaravelRating\Models\Rating;
use Nagy\LaravelRating\Traits\Like\CanLike;
use Nagy\LaravelRating\Traits\Rate\CanRate;
use Nagy\LaravelRating\Traits\Vote\CanVote;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $clients_count
 * @property-read Collection|Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read Collection|Rating[] $likes
 * @property-read int|null $likes_count
 * @property-read Collection|Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Rating[] $ratings
 * @property-read int|null $ratings_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read int|null $tokens_count
 * @property-read Collection|Rating[] $votes
 * @property-read int|null $votes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property int $can_email
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCanEmail($value)
 */
class User extends Authenticatable implements FilamentUser
{
    use CanLike, CanRate, CanVote, Favoriteability, HasRoles, Notifiable, SoftDeletes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'can_email',
    ];

    public $guard_name = 'backpack';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'notification_preferences' => 'array',
    ];

    public function wantsNotification(NotificationType $type): bool
    {
        return (bool) ($this->notification_preferences[$type->value] ?? $type->enabledByDefault());
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('view-admin');
    }

    public function loadouts()
    {
        return $this->hasMany(Loadout::class);
    }

    public function authorAttributes(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'url' => route('user.profile', $this->id),    // optional
            //            'avatar' => 'gravatar', // optional
        ];
    }
}
