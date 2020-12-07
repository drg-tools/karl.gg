<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use App\Character;
use App\Gun;
use App\Overclock;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class BuildMetricController extends Controller
{
    public function index(Request $request, $gun, $combo)
    {
        // SEOTools::setTitle('Deep Rock Galactic Loadouts');
        // TODO: SEO

        $build = BuildMetric::where([
            ['gun_id', '=', $gun],
            ['build_combination', '=', $combo],
        ])->firstOrFail();

        $build_gun = $build->gun;

        $mod_matrix = $this->getModMatrix($build_gun, $combo);
        $combo_array = str_split($combo);
        $overclock = false;
        if ($combo_array[5] != '-') {
            $overclock = $this->getOverclock($gun, $combo_array[5]);
        }

        return view('asv.index', [
            'buildMetrics'  => $build,
            'gun'           => $build_gun,
            'modMatrix'     => $mod_matrix,
            'combo'         => $combo,
            'overclock'     => $overclock ? $overclock : '',
        ]);
    }

    private function getModMatrix($gun, $combo)
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

    private function getOverclock($gun, $index)
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
