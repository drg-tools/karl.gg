<?php

namespace App\Http\Controllers;

use App\Models\BuildMetric;
use App\Models\Gun;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BuildMetricController extends Controller
{
    public function show(Request $request, string $gun, string $combo): View
    {
        $combo = BuildMetric::normalizeCombination($combo);
        $build = BuildMetric::where([
            ['gun_id', '=', $gun],
            ['build_combination', '=', $combo],
        ])->first();

        if (! $build) {
            $build_gun = Gun::findOrFail($gun);
            SEOTools::setTitle("{$build_gun->name} Advanced Statistics");

            return view('asv.unavailable', ['gun' => $build_gun]);
        }

        $build_gun = $build->gun;
        $displayCombo = strtr($combo, 'ABC', '123');

        SEOTools::setTitle(str_replace('"', '', "{$build_gun->name} {$displayCombo} Advanced Statistics"));

        $mod_matrix = $build->getModMatrix($build_gun, $combo);
        $combo_array = str_split($combo);
        $overclock = false;
        if ($combo_array[5] != '-') {
            $overclock = $build->getOverclock($build_gun, $combo_array[5]);
        }

        return view('asv.show', [
            'buildMetrics' => $build,
            'gun' => $build_gun,
            'modMatrix' => $mod_matrix,
            'combo' => $displayCombo,
            'overclock' => $overclock ? $overclock : '',
        ]);
    }
}
