<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ModFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function gun($id)
    {
        return $this->where('gun_id', $id);
    }

    public function mod($id)
    {
        return $this->where('id', $id);
    }

    public function search($name)
    {
        $this->whereLike('mod_name', $name);
    }
}
