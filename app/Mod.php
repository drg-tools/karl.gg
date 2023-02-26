<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Mod.
 *
 * @property int $id
 * @property int $character_id
 * @property int $gun_id
 * @property int $mod_tier
 * @property string $mod_index
 * @property string $mod_name
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
 * @property string $mod_type
 * @property-read \App\Character $character
 * @property-read \App\Gun $gun
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Loadout[] $loadouts
 * @property-read int|null $loadouts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Mod filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mod paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mod simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereBismorCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereCreditsCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereCroppaCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereEnorPearlCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereGunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereJadizCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereMagniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereModIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereModName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereModTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereModType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereTextDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mod whereUmaniteCost($value)
 * @mixin \Eloquent
 */
class Mod extends Model
{
    use HasFactory;
    use Filterable, CrudTrait;

    public $identifiableAttribute = 'name';
    protected $guarded = ['id'];

    public function gun()
    {
        return $this->belongsTo(Gun::class, 'gun_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class, 'loadout_mod');
    }

    public function getImageSvgAttribute()
    {
        return "/assets/mods/optimized/{$this->icon}.svg";
    }

    public function getCleanTextDescriptionAttribute()
    {
        return addslashes(Str::of($this->text_description)
            ->remove(["\r", "\n"]));
    }
}
