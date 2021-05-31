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
    public $relations = [];

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
    }

    // public function secondaries($secondaryIds)
    // {
    //     return $this->whereHas('character', function ($query) use ($secondaryIds) {
    //         return $query->whereIn('id', $secondaryIds);
    //     });
    // }
}
