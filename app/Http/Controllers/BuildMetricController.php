<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use App\Character;
use App\Gun;
use App\Mod;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class BuildMetricController extends Controller
{
    public function index(Request $request, $class, $gun, $combo)
    {
        // SEOTools::setTitle('Deep Rock Galactic Loadouts');

        //TODO: Equipment handling

        $build = BuildMetric::where([
            ['character_id', '=', $class],
            ['gun_id', '=', $gun],
            ['build_combination', '=', $combo],
        ])->get();

        $build_gun = Gun::where('id', $gun)->get();
        $gun_icon = asset('/assets/'.$build_gun[0]->image.'.svg');
        // dd($gun_icon);
        $build_character = Character::where('id', $class)->get();

        // TODO: Get Mod Matrix
        // TODO: Get Overclock based on combo
        $mod_matrix = $this->getModMatrix($gun, $combo);

        return view('asv.index', [
            'build'     => $build,
            'gun'       => $build_gun,
            'gunIcon'   => $gun_icon,
            'character' => $build_character,
            'modMatrix' => $mod_matrix,
        ]);
    }

    private function getModMatrix($gun, $combo)
    {
        $selected_index = [];
        $gun_object = Gun::find($gun);
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
}
