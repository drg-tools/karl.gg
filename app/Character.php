<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    public function guns()
    {
        return $this->hasMany(Gun::class);
    }
}
