<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use App\Post;
use Illuminate\Support\Carbon;
use SEOMeta;

class DashboardController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Dashboard');

        $recentTopLoadouts = $this->getRecentTopLoadouts();
        $latestLoadouts = $this->getLatestLoadouts();
        $posts = $this->getLatestPosts();

        return view('dashboard.index', [
            'recentTopLoadouts' => $recentTopLoadouts,
            'latestLoadouts' => $latestLoadouts,
            'latestPosts' => $posts,
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
            ->take(3)
            ->get();

        return $recentTopLoadouts;
    }

    private function getLatestLoadouts()
    {
        $latestLoadouts = Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withCount('votes')
            ->with('character', 'creator', 'mods.gun')
            ->latest()
            ->take(3)
            ->get();

        return $latestLoadouts;
    }

    private function getLatestPosts()
    {
        $latestPosts = Post::orderBy('created_at', 'desc')->take(3)->get();

        return $latestPosts;
    }
}
