<?php

namespace App\Http\Controllers;

use App\Loadout;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $engiLoadouts = Loadout::where('character_id', 1)->withCount('votes')->orderBy('votes_count',
            'desc')->take(3)->get();
        $scoutLoadouts = Loadout::where('character_id', 2)->withCount('votes')->orderBy('votes_count',
            'desc')->take(3)->get();
        $drillerLoadouts = Loadout::where('character_id', 3)->withCount('votes')->orderBy('votes_count',
            'desc')->take(3)->get();
        $gunnerLoadouts = Loadout::where('character_id', 4)->withCount('votes')->orderBy('votes_count',
            'desc')->take(3)->get();

        return view('dashboard.index', [
            'engiLoadouts' => $engiLoadouts,
            'scoutLoadouts' => $scoutLoadouts,
            'drillerLoadouts' => $drillerLoadouts,
            'gunnerLoadouts' => $gunnerLoadouts,
        ]);
    }
}
