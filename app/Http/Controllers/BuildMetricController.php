<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use App\Character;
use App\Gun;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class BuildMetricController extends Controller
{
    public function index(Request $request, $class, $gun, $combo)
    {
        // SEOTools::setTitle('Deep Rock Galactic Loadouts');

        $build = BuildMetric::where([
            ['character_id', '=', $class],
            ['gun_id', '=', $gun],
            ['build_combination', '=', $combo],
        ])->get();

        $build_gun = Gun::where('id', $gun)->get();
        $build_character = Character::where('id', $class)->get();

        return view('asv.index', [
            'build'     => $build,
            'gun'       => $build_gun,
            'character' => $build_character,
        ]);
    }
}
