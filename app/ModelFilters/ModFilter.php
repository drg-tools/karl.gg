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
        $this->where('gun_id', $id);
    }

    public function row($row)
    {
        $this->where('row', $row);
    }

    public function column($column)
    {
        $this->where('column', $column);
    }

    public function name($name)
    {
        $this->whereLike('name', $name);
    }
}
