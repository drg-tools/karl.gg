<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Patch extends Model
{
    use Filterable, CrudTrait;

    protected $primaryKey = 'id';
}
