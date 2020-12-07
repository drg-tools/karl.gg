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

    public function getModMatrix($gun, $combo)
    {
        $selected_index = [];
        $gun_object = $gun;
        $gun_mods = $gun_object->mods->groupBy('mod_tier');

        $combo_array = str_split($combo);

        foreach ($combo_array as $key => $tier) {
            $selected = false;
            if ($tier != '-') {
                $selected = true;
            }
            // dd($tier);
            $selected_index[$key + 1] = ['value' => $tier, 'selected' => $selected];
        }
        $matrixArray = compact('gun_mods', 'selected_index');

        return $matrixArray;
    }

    public function getOverclock($gun, $index)
    {
        $gun_object = Gun::findOrFail($gun);
        $overclock = Overclock::where([
            ['character_id', '=', $gun_object->character_id],
            ['gun_id', '=', $gun_object->id],
            ['overclock_index', '=', $index],
        ])->firstOrFail();

        return $overclock;
    }

}
