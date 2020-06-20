<?php

namespace App;

use EloquentFilter\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ModStat extends Model
{
    use Filterable, CrudTrait;

    public function gun()
    {
        return $this->belongsTo(Gun::class);
    }
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
