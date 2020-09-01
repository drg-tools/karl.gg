<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Character.
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EquipmentMod[] $equipment_mods
 * @property-read int|null $equipment_mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Equipment[] $equipments
 * @property-read int|null $equipments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Gun[] $guns
 * @property-read int|null $guns_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mod[] $mods
 * @property-read int|null $mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Overclock[] $overclocks
 * @property-read int|null $overclocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Throwable[] $throwables
 * @property-read int|null $throwables_count
 * @method static \Illuminate\Database\Eloquent\Builder|Character newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Character newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Character query()
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Character extends Model
{
    use CrudTrait;

    public function guns()
    {
        return $this->hasMany(Gun::class);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function equipment_mods()
    {
        return $this->hasMany(EquipmentMod::class);
    }

    public function throwables()
    {
        return $this->hasMany(Throwable::class);
    }

    public function mods()
    {
        return $this->hasMany(Mod::class);
    }

    public function overclocks()
    {
        return $this->hasMany(Overclock::class);
    }
}
