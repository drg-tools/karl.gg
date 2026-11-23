<?php

namespace App\Models;

use App\Events\LoadoutSaving;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Nagy\LaravelRating\Models\Rating;
use Nagy\LaravelRating\Traits\Like\Likeable;
use Nagy\LaravelRating\Traits\Vote\Votable;

/**
 * App\Models\Loadout.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $user_id
 * @property int $character_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $throwable_id
 * @property bool $is_hidden
 * @property-read Character $character
 * @property-read User|null $creator
 * @property-read Collection|EquipmentMod[] $equipment_mods
 * @property-read int|null $equipment_mods_count
 * @property-read Collection|Favorite[] $favorites
 * @property-read int $favorites_count
 * @property-read null $primary_gun
 * @property-read null $secondary_gun
 * @property-read Collection|Rating[] $likes
 * @property-read int|null $likes_count
 * @property-read Collection|Mod[] $mods
 * @property-read int|null $mods_count
 * @property-read Collection|Overclock[] $overclocks
 * @property-read int|null $overclocks_count
 * @property-read Throwable|null $throwable
 * @property-read Collection|Rating[] $votes
 * @property-read int|null $votes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereThrowableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout whereUserId($value)
 *
 * @mixin \Eloquent
 *
 * @property int $patch_id
 * @property-read Patch $patch
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Loadout wherePatchId($value)
 * @method static Builder|Loadout onOlderPatch()
 */
class Loadout extends Model
{
    use Favoriteable, Filterable, Likeable, Sortable, Votable;
    use HasFactory;

    public $sortable = ['name', 'description', 'character_id', 'throwable_id', 'created_at', 'updated_at'];

    public $sortableAs = ['votes_sum_value'];

    private static $whiteListFilter = [
        'creator',
        'name',
        'description',
        'character_id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = ['name', 'description', 'character_id', 'throwable_id', 'user_id'];

    protected $hidden = ['creator'];

    protected $casts = ['is_hidden' => 'boolean'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => LoadoutSaving::class,
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function mods()
    {
        return $this->belongsToMany(Mod::class, 'loadout_mod');
    }

    public function overclocks()
    {
        return $this->belongsToMany(Overclock::class, 'loadout_overclock');
    }

    public function equipment_mods()
    {
        return $this->belongsToMany(EquipmentMod::class, 'loadout_equipment_mod');
    }

    public function throwable()
    {
        return $this->belongsTo(Throwable::class);
    }

    public function patch()
    {
        return $this->belongsTo(Patch::class);
    }

    public function scopeListed(Builder $query): Builder
    {
        return $query->where('loadouts.is_hidden', false);
    }

    public function setHiddenFromLists(bool $hidden): void
    {
        $this->is_hidden = $hidden;

        static::withoutTimestamps(fn (): bool => $this->saveQuietly());
    }

    public function scopeOnOlderPatch(Builder $query)
    {
        $currentPatch = Patch::current();

        return $query->whereHas('patch', function ($patch) use ($currentPatch) {
            $patch->where('id', '!=', $currentPatch->id);
        });
    }

    public static function getUpvotesCount($id)
    {
        $loadout = Loadout::findOrFail($id);

        return $loadout->upVotesCount();
    }

    /**
     * The weapon's upgrade code: one number per tier, a dash for a tier left open (e.g. "21–32").
     * Matches the code box in the compact builder, so players can copy it straight in.
     */
    public function upgradeCode(int $gunId, int $tiers = 5): string
    {
        $chosen = $this->mods->where('gun_id', $gunId)->pluck('mod_index', 'mod_tier');

        return collect(range(1, $tiers))
            ->map(fn (int $tier): string => strtr($chosen->get($tier) ?? '–', 'ABC', '123'))
            ->join('');
    }

    /**
     * We don't have a native relationship to guns, so we must traverse the mods to derive primary weapon.
     *
     * @return |null
     */
    public function getPrimaryGunAttribute()
    {
        return $this->getGunFromMods(1);
    }

    /**
     * @return mixed |null
     */
    public function getSecondaryGunAttribute()
    {
        return $this->getGunFromMods(2);
    }

    /**
     * @return mixed |null
     */
    public function getEquipmentsAttribute()
    {
        return $this->getEquipmentsFromMods();
    }

    /**
     * We don't have a native relationship to guns, so we must traverse the mods to derive weapon.
     *
     * @return mixed
     */
    private function getGunFromMods($slot)
    {
        $grouped = $this->mods->groupBy('gun.character_slot')
            ->get($slot);

        if (! $grouped || ! $grouped->first()) {
            return null;
        }

        return $grouped->first()->gun;
    }

    /**
     * We don't have a native relationship to guns, so we must traverse the mods to derive weapon.
     *
     * @param  $slot
     * @return mixed
     */
    private function getEquipmentsFromMods()
    {
        $grouped = $this->equipment_mods->groupBy('equipment_id');

        if (! $grouped || ! $grouped->first()) {
            return null;
        }

        return $grouped;
    }

    /**
     * Saves the model without running any model events.
     *
     * @return bool
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
