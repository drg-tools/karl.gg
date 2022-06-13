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
        'overclocks' => ['overclocks'], // Added so we can grab our overclocks
        'patches' => ['latest'], // Added for patch filter
    ];

    public function character($id)
    {
        $this->where('character_id', $id);
    }

    public function favorites()
    {
        $this->whereHas('favorites', fn ($query) => $query->where('user_id', auth()->id()))->get();
    }

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

    public function hasOverclock($ocVal)
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

    public function overclocks($ocIds)
    {
        return $this->whereHas('overclocks', function ($query) use ($ocIds) {
            return $query->whereIn('overclock_id', $ocIds);
        });
    }

    public function hasGuide($guideVal)
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

    public function mods($modIds)
    {
        return $this->whereHas('mods', function ($query) use ($modIds) {
            return $query->whereIn('mods.id', $modIds);
        });
    }

    public function isCurrentPatch($currentPatch)
    {
        if ($currentPatch == 0) {
            return;
        } else {
            return $this->where('patch_id', \App\Patch::current()->id);
        }
    }
}
