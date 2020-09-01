<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Equipment
 *
 * @property int $id
 * @property int $character_id
 * @property string $name
 * @property string $json_stats
 * @property string $icon
 * @property string $eq_type
 * @property int $patch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Character $character
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EquipmentMod[] $equipment_mods
 * @property-read int|null $equipment_mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereEqType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Equipment extends Model
{
    use Filterable, CrudTrait;

    protected $table = 'equipment';

    protected $fillable = [
        'character_id',
        'name',
        'json_stats',
        'icon',
        'eq_type',
        'patch_id',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function equipment_mods()
    {
        return $this->hasMany(EquipmentMod::class);
    }

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class, 'loadout_equipment');
    }
}
