<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $loadouts = Cache::remember('users', 1800, function () {
            return $this->getTopLoadouts();
        });

        return view('dashboard.index', [
            'loadouts' => $loadouts,
        ]);
    }

    private function getTopLoadouts()
    {
        $loadouts = collect();
        $characterIds = Character::pluck('id')->sortBy('id');

        foreach ($characterIds as $characterId) {
            $characterLoadouts = Loadout::where('character_id', $characterId)
                ->withCount('votes')
                ->with('character', 'creator', 'mods.gun')
                ->orderBy('votes_count', 'desc')
                ->take(3)
                ->get();
            $loadouts->push($characterLoadouts);
        }

        return $loadouts;
    }
}
