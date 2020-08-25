<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Nagy\LaravelRating\Traits\Like\Likeable;
use Nagy\LaravelRating\Traits\Vote\Votable;

class Loadout extends Model
{
    use Favoriteable, Filterable, CrudTrait, Votable, Likeable, Sortable;

    public $sortable = ['name', 'description', 'character_id', 'throwable_id'];
    protected $fillable = ['name', 'description', 'character_id', 'throwable_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function mods()
    {
        return $this->belongsToMany(Mod::class, 'loadout_mod');
    }

    public function overclocks()
    {
        return $this->belongsToMany(Overclock::class, 'loadout_overclock');
    }

    public function equipment_mods()
    {
        return $this->belongsToMany(EquipmentMod::class, 'loadout_equipment_mod');
    }

    public function throwable()
    {
        return $this->hasOne(Throwable::class, 'id', 'throwable_id');
    }

    public static function getUpvotesCount($id)
    {
        $loadout = Loadout::findOrFail($id);

        return $loadout->upVotesCount();
    }

    /**
     * We don't have a native relationship to guns, so we must traverse the mods to derive primary weapon
     *
     * @return |null
     */
    public function getPrimaryGunAttribute()
    {
        return $this->getGunFromMods(0);
    }

    /**
     *
     *
     * @return |null
     */
    public function getSecondaryGunAttribute()
    {
        return $this->getGunFromMods(1);
    }

    /**
     * We don't have a native relationship to guns, so we must traverse the mods to derive weapon
     *
     * @param $slot
     * @return mixed
     */
    private function getGunFromMods($slot)
    {
        $grouped = $this->mods->groupBy('gun_id')
            ->sortKeys() // sort by gun_id (primary should now be first)
            ->values() // re-index array
            ->get($slot); // Get nth item

        if (!$grouped || !$grouped->first()) {
            return null;
        }

        return $grouped->first()->gun;
    }
}
