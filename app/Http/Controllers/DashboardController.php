<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use App\Overclock;
use App\Patch;
use App\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use SEOMeta;

class DashboardController extends Controller
{
    /**
     * How long the patch-wide pick statistics stay cached, in seconds.
     */
    private const STATS_CACHE_SECONDS = 900;

    public function index(): View
    {
        SEOMeta::setTitle('Dashboard');

        $currentPatch = Patch::current();

        return view('dashboard.index', [
            'currentPatch' => $currentPatch,
            'patchStats' => $this->getPatchStats($currentPatch),
            'recentTopLoadouts' => $this->getRecentTopLoadouts(),
            'latestLoadouts' => $this->getLatestLoadouts(),
            'latestPosts' => $this->getLatestPosts(),
        ]);
    }

    /**
     * Loadout counts per class and the most-picked overclocks for the current patch.
     *
     * @return array{totalLoadouts: int, patchLoadouts: int, classes: Collection<int, Character>, popularOverclocks: Collection<int, Overclock>}
     */
    private function getPatchStats(?Patch $currentPatch): array
    {
        return Cache::remember(
            'dashboard.patch-stats.'.($currentPatch?->id ?? 'none'),
            self::STATS_CACHE_SECONDS,
            function () use ($currentPatch): array {
                $onCurrentPatch = fn (Builder $query) => $query->where('patch_id', $currentPatch?->id);

                $classes = Character::withCount(['loadouts' => $onCurrentPatch])
                    ->orderBy('name')
                    ->get();

                $popularOverclocks = Overclock::with('gun', 'character')
                    ->withCount(['loadouts' => $onCurrentPatch])
                    ->orderByDesc('loadouts_count')
                    ->take(6)
                    ->get()
                    ->filter(fn (Overclock $overclock) => $overclock->loadouts_count > 0)
                    ->values();

                return [
                    'totalLoadouts' => Loadout::count(),
                    'patchLoadouts' => (int) $classes->sum('loadouts_count'),
                    'classes' => $classes,
                    'popularOverclocks' => $popularOverclocks,
                ];
            }
        );
    }

    /**
     * @return Collection<int, Loadout>
     */
    private function getRecentTopLoadouts(): Collection
    {
        return Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withSum('votes', 'value')
            ->with('character', 'creator', 'mods.gun')
            ->orderBy('votes_sum_value', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @return Collection<int, Loadout>
     */
    private function getLatestLoadouts(): Collection
    {
        return Loadout::where('created_at', '>', Carbon::now()->subDays(14))
            ->withSum('votes', 'value')
            ->with('character', 'creator', 'mods.gun')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * @return Collection<int, Post>
     */
    private function getLatestPosts(): Collection
    {
        return Post::published()->latest('published_at')->take(3)->get();
    }
}
