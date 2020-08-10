<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    use Filterable, CrudTrait;

    public $identifiableAttribute = 'name';
    protected $guarded = ['id'];
    public $timestamps = false;

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
}
