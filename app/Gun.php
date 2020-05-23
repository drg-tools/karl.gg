<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Gun extends Model
{
    use Filterable;

    public function mods()
    {
        return $this->hasMany(Mod::class);
    }
}
