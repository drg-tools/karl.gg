<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

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
