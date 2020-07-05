<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

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
