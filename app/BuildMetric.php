<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class BuildMetric extends Model
{
    use CrudTrait;
    protected $guarded = ['id'];
    public function gun()
    {
        return $this->belongsTo(Gun::class, 'gun_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }
    public function patch()
    {
        return $this->belongsTo(Patch::class);
    }
    
}
