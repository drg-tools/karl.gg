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
        'creator' => ['user_id'],
    ];

    public function character($id)
    {
        $this->where('character_id', $id);
    }

    public function search($name)
    {
        $this->whereLike('loadouts.name', $name);
    }

    public function creator($ids)
    {
        return $this->whereHas('creator', function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        });
    }
}
