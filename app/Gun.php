<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Gun extends Model
{
    use Filterable, CrudTrait;

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

    public function loadouts()
    {
        return $this->belongsToMany(Loadout::class, 'loadout_gun');
    }
}
