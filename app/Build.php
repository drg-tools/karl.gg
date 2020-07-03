<?php

namespace App;

use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use Favoriteable;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function guns()
    {
        return $this->hasMany(Gun::class);
    }

    public function mods()
    {
        return $this->belongsToMany(Mod::class);
    }
}
