<?php

namespace App\Http\Controllers;

use SEOMeta;
use App\Character;
use App\Loadout;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Dashboard');

        $allTimeTopLoadouts = Cache::remember('allTimeTopLoadouts', 1800, function () {
            return $this->getTopLoadoutsAllTime();
        });
        $recentTopLoadouts = $this->getRecentTopLoadouts();
        $latestLoadouts = $this->getLatestLoadouts();

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
        // TODO: Extract to helper / model function
        $recentTopLoadouts = Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withCount('votes')
            ->with('character', 'creator', 'mods.gun')
            ->orderBy('votes_count', 'desc')
            ->take(12)
            ->get();

        return $recentTopLoadouts;
    }

    private function getLatestLoadouts()
    {
        $latestLoadouts = Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withCount('votes')
            ->with('character', 'creator', 'mods.gun')
            ->latest()
            ->take(12)
            ->get();

        return $latestLoadouts;
    }
}
