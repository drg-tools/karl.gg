<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    use Filterable;

    public function gun()
    {
        return $this->belongsTo(Gun::class);
    }
}
