<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gun extends Model
{
    public function mods()
    {
        return $this->hasMany(Mod::class);
    }
}
