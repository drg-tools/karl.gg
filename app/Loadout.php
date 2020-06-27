<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Loadout extends Model
{
    use Favoriteable, Filterable, CrudTrait;

    protected $fillable = ['name', 'description', 'character_id'];

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

    public function overclocks()
    {
        return $this->belongsToMany(Overclock::class);
    }
}
