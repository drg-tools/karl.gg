<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\EquipmentMod.
 *
 * @property int $id
 * @property int $character_id
 * @property int $equipment_id
 * @property int $mod_tier
 * @property string $mod_index
 * @property string $mod_name
 * @property string $description
 * @property string $json_stats
 * @property int $credits_cost
 * @property int $magnite_cost
 * @property int $bismor_cost
 * @property int $umanite_cost
 * @property int $croppa_cost
 * @property int $enor_pearl_cost
 * @property int $jadiz_cost
 * @property string $icon
 * @property string $mod_type
 * @property int $patch_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Character $character
 * @property-read \App\Equipment $equipment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod query()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereBismorCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereCreditsCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereCroppaCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereEnorPearlCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereJadizCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereMagniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereModIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereModName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereModTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereModType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod wherePatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereUmaniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentMod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EquipmentMod extends Model
{
    use Filterable, CrudTrait;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class, 'loadout_equipment_mod');
    }
}
