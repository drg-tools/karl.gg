<?php

namespace App\Http\Controllers;

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

    private function getRecentTopLoadouts()
    {
        // TODO: Extract to helper / model function
        $recentTopLoadouts = Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withSum('votes', 'value')
            ->with('character', 'creator', 'mods.gun')
            ->orderBy('votes_sum_value', 'desc')
            ->take(5)
            ->get();

        return $recentTopLoadouts;
    }

    private function getLatestLoadouts()
    {
        $latestLoadouts = Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withSum('votes', 'value')
            ->with('character', 'creator', 'mods.gun')
            ->latest()
            ->take(5)
            ->get();

        return $latestLoadouts;
    }

    private function getLatestPosts()
    {
        $latestPosts = Post::published()->latest('published_at')->take(3)->get();

        return $latestPosts;
    }
}
