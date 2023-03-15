<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Gun.
 *
 * @property int $id
 * @property string $name
 * @property string $gun_class
 * @property int $character_slot
 * @property int $character_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $json_stats
 * @property string|null $image
 * @property-read \App\Character $character
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mod[] $mods
 * @property-read int|null $mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Overclock[] $overclocks
 * @property-read int|null $overclocks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Gun filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gun paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gun simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereCharacterSlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereGunClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gun whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Gun extends Model
{
    use HasFactory;
    use Filterable, CrudTrait;
    protected $guarded = ['id'];

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class);
    }

    public function mods()
    {
        return $this->hasMany(Mod::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function overclocks()
    {
        return $this->hasMany(Overclock::class);
    }
}
