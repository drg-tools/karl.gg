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
        // Object to determine if we have a selected mod in a tier
        $selected_index = [];

        // Mods for our gun, nicely grouped into their proper tiers.
        // It just happens to be in the right order, so we don't need to do any further transformation
        // We will cross-index this with out selected_index
        $gun_mods = $gun->mods->groupBy('mod_tier');

        // Let's break our Combo String into an actual array that we can work with.
        // Allows us to check for selected items
        $combo_array = str_split($combo);

        // Iterate through the combo array to see if we have a selected item on the tier
        // Goal here is to get the mod that is selected for each tier in an object for our view
        foreach ($combo_array as $key => $tier) {
            // By default, assume the tier is empty
            $selected = false;

            // If our combo string is NOT '-' we have something selected
            if ($tier != '-') {
                $selected = true;
            }

            // Our selected_index object needs to correspond to our mod tiers
            // Take our $key which is basically just our loop iterator, and +1 to get the proper tier for our array
            // Save the value of our tier, and a quick boolean object if it's selected
            $selected_index[$key + 1] = ['value' => $tier, 'selected' => $selected];
        }

        // Compact our two arrays into one to return to the view
        // We will use both of these arrays to construct our frontend mod matrix
        // We will be able to show all the mods in the right order, plus the selected ones
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
