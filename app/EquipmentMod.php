<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

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
