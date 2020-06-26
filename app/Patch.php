<?php

namespace App;

use EloquentFilter\Filterable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Patch extends Model
{
    use Filterable, CrudTrait;

    protected $primaryKey = 'id';
}
