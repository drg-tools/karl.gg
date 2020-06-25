<?php

namespace App;

use EloquentFilter\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;

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
    public function modstats()
    {
        return $this->belongsToMany(ModStat::class);
    }
    public function overclocks()
    {
        return $this->belongsToMany(Overclock::class);
    }
}
