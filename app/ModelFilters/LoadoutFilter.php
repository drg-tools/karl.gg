<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class LoadoutFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [
        'mods' => ['mods'], // Added so we can grab our weapon_id's
    ];

    public function characters($charIds)
    {
        return $this->whereHas('character', function ($query) use ($charIds) {
            return $query->whereIn('id', $charIds);
        });
    }

    public function search($name)
    {
        $this->whereLike('loadouts.name', $name);
    }

    public function overclocks($ocVal)
    {
        // Users requested an ability to filter out loadouts with overclocks, as they may be new to the game and don't have the $$ for the OC's yet
        if ($ocVal === null) {
            return;
        } elseif ($ocVal == 'Yes') {
            return $this->whereHas('overclocks');
        } else {
            return $this->whereDoesntHave('overclocks');
        }
    }

    public function guide($guideVal)
    {
        // Users requested an ability to filter out loadouts without guides, especially for new players
        if ($guideVal === null) {
            return;
        } elseif ($guideVal == 'Yes') {
            return $this->where('description', '!=', null);
        } else {
            return $this->where('description', null);
        }
    }

    public function primaries($primaryIds)
    {
        // I don't need to make a distinction when pulling the guns, I just need a loadout where the gun_id is this
        // It doesn't impact UI so we don't need to know specifically "primary or secondary"
        // These methods simply correspond with the input boxes
        return $this->whereHas('mods', function ($query) use ($primaryIds) {
            return $query->whereIn('gun_id', $primaryIds);
        });
    }

    public function secondaries($secondaryIds)
    {
        // I don't need to make a distinction when pulling the guns, I just need a loadout where the gun_id is this
        // It doesn't impact UI so we don't need to know specifically "primary or secondary"
        // These methods simply correspond with the input boxes
        return $this->whereHas('mods', function ($query) use ($secondaryIds) {
            return $query->whereIn('gun_id', $secondaryIds);
        });
    }
}
