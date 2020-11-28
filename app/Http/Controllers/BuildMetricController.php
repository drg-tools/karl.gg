<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Mod;
use App\Loadout;
use App\Overclock;
use App\BuildMetric;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Str;

class BuildMetricController extends Controller
{
    public function index(Request $request,$class,$gun,$combo)
    {
        // SEOTools::setTitle('Deep Rock Galactic Loadouts');
        // TODO: SEO

        $build = BuildMetric::where([
            ['character_id','=', $class],
            ['gun_id','=', $gun],
            ['build_combination','=', $combo]
        ])->get();

        $build_gun = Gun::where('id',$gun)->get();
        $gun_icon = asset('/assets/'.$build_gun[0]->image.'.svg');
        $build_character = Character::where('id',$class)->get();

        $mod_matrix = $this->getModMatrix($gun,$combo);
        $combo_array = str_split($combo);
        if($combo_array[5] != "-") {
            $overclock = $this->getOverclock($gun,$combo_array[5]);
        }
        
        return view('asv.index', [
            'buildMetrics'  => $build,
            'gun'           => $build_gun,
            'gunIcon'       => $gun_icon,
            'character'     => $build_character,
            'modMatrix'     => $mod_matrix,
            'combo'         => $combo,
            'overclock'     => $overclock ? $overclock : '',
        ]);
    }

    private function getModMatrix($gun,$combo) 
    {
        $selected_index = [];
        $gun_object = Gun::find($gun);
        $gun_mods = $gun_object->mods->groupBy('mod_tier');
        
        $combo_array = str_split($combo);
        
        foreach($combo_array as $key => $tier) {
            $selected = false;
            if($tier != '-') {
                $selected = true;
            }
            // dd($tier);
            $selected_index[$key+1] = array('value' => $tier, 'selected' => $selected );
        }
        $matrixArray = compact('gun_mods','selected_index');

        return $matrixArray;
    }

    private function getOverclock($gun, $index)
    {
        $gun_object = Gun::find($gun);
        $overclock = Overclock::where([
            ['character_id','=', $gun_object->character_id],
            ['gun_id','=', $gun_object->id],
            ['overclock_index','=', $index]
        ])->get();

        return $overclock;
    }
}
