<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class BuildMetricController extends Controller
{
    public function show(Request $request, $gun, $combo)
    {
        // SEOTools::setTitle('Deep Rock Galactic Loadouts');
        // TODO: SEO

        $build = BuildMetric::where([
            ['gun_id', '=', $gun],
            ['build_combination', '=', $combo],
        ])->firstOrFail();

        $build_gun = $build->gun;

        $mod_matrix = $build->getModMatrix($build_gun, $combo);
        $combo_array = str_split($combo);
        $overclock = false;
        if ($combo_array[5] != '-') {
            $overclock = $build->getOverclock($build_gun, $combo_array[5]);
        }

        return view('asv.show', [
            'buildMetrics'  => $build,
            'gun'           => $build_gun,
            'modMatrix'     => $mod_matrix,
            'combo'         => $combo,
            'overclock'     => $overclock ? $overclock : '',
        ]);
    }
}
