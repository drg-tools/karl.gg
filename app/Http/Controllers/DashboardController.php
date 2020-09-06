<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $allTimeTopLoadouts = Cache::remember('allTimeTopLoadouts', 1800, function () {
            return $this->getTopLoadoutsAllTime();
        });
        $recentTopLoadouts = Cache::remember('recentTopLoadouts', 1800, function () {
            return $this->getRecentTopLoadouts();
        });
        $latestLoadouts = Cache::remember('latestLoadouts', 1800, function () {
            return $this->getLatestLoadouts();
        });

        return view('dashboard.index', [
            'allTimeTopLoadouts' => $allTimeTopLoadouts,
            'recentTopLoadouts' => $recentTopLoadouts,
            'latestLoadouts' => $latestLoadouts,
        ]);
    }

    private function getTopLoadoutsAllTime()
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

    private function getRecentTopLoadouts()
    {
        $loadouts = collect();
        $characterIds = Character::pluck('id')->sortBy('id');

        foreach ($characterIds as $characterId) {
            $characterLoadouts = Loadout::where([
                ['character_id', $characterId],
                ['created_at', '>', Carbon::now()->subDays(14)], // Loadouts created in the past 2 weeks
            ])
                ->withCount('votes')
                ->with('character', 'creator', 'mods.gun')
                ->orderBy('votes_count', 'desc')
                ->take(5)
                ->get();
            $loadouts->push($characterLoadouts);
        }

        return $loadouts;
    }

    private function getLatestLoadouts()
    {
        $loadouts = collect();
        $characterIds = Character::pluck('id')->sortBy('id');

        foreach ($characterIds as $characterId) {
            $characterLoadouts = Loadout::where([
                ['character_id', $characterId],
                ['created_at', '>', Carbon::now()->subDays(14)], // Loadouts created in the past 2 weeks
            ])
                ->with('character', 'creator', 'mods.gun')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
            $loadouts->push($characterLoadouts);
        }

        return $loadouts;
    }
}
