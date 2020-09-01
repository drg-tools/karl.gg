<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Overclock.
 *
 * @property int $id
 * @property int $character_id
 * @property int $gun_id
 * @property string $overclock_type
 * @property int $overclock_index
 * @property string $overclock_name
 * @property int $credits_cost
 * @property int $magnite_cost
 * @property int $bismor_cost
 * @property int $umanite_cost
 * @property int $croppa_cost
 * @property int $enor_pearl_cost
 * @property int $jadiz_cost
 * @property string $text_description
 * @property string $json_stats
 * @property string $icon
 * @property int $patch_id
 * @property-read \App\Character $character
 * @property-read \App\Gun $gun
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereBismorCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereCreditsCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereCroppaCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereEnorPearlCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereGunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereJadizCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereMagniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereOverclockIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereOverclockName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereOverclockType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock wherePatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereTextDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overclock whereUmaniteCost($value)
 * @mixin \Eloquent
 */
class Overclock extends Model
{
    use Filterable, CrudTrait;

    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function gun()
    {
        return $this->belongsTo(Gun::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class, 'loadout_overclock');
    }
}
