<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    use Filterable, CrudTrait;

    public $identifiableAttribute = 'name';

    public function gun()
    {
        return $this->belongsTo(Gun::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
