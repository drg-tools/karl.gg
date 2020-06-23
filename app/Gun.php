<?php

namespace App;

use EloquentFilter\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Gun extends Model
{
    use Filterable,CrudTrait;

    public function mods()
    {
        return $this->hasMany(Mod::class);
    }

    public function character()
    {
	      return $this->belongsTo(Character::class);
    }
    public function modstats()
    {
        return $this->hasMany(ModStat::class);
    }
    public function overclocks()
    {
        return $this->hasMany(Overclock::class);
    } 
}
