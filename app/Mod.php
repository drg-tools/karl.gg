<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    public function gun()
    {
        return $this->belongsTo(Gun::class);
    }
}
