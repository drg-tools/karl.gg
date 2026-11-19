<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use App\LoadoutBrowseQuery;
use App\Patch;
use App\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Nagy\LaravelRating\Models\Rating;

class ProfileController extends Controller
{
    /**
     * Class identity colours shared with the dashboard, builder and loadout page.
     *
     * @var array<string, string>
     */
    private const CLASS_ACCENTS = [
        'Driller' => '#ebc64d',
        'Engineer' => '#eb745d',
        'Gunner' => '#9cba6f',
        'Scout' => '#68aaf6',
    ];

    /**
     * Show a user's public profile: their stats, best builds and every loadout they've shared.
     */
    public function index(Request $request, int $id): View
    {
        $user = User::findOrFail($id);
        $currentPatchId = Patch::current()?->id;
        $isOwner = $request->user()?->is($user) ?? false;
        $profileLoadouts = Loadout::query()->where('user_id', $user->id)
            ->unless($isOwner, fn (Builder $query): Builder => $query->listed());

        /** @var Collection<int, int> $classCounts loadout count keyed by character id */
        $classCounts = (clone $profileLoadouts)->toBase()
            ->selectRaw('character_id, count(*) as aggregate')
            ->groupBy('character_id')
            ->pluck('aggregate', 'character_id')
            ->map(fn ($count) => (int) $count);
        $loadoutCount = $classCounts->sum();

        $sort = $request->query('sort');
        $sort = is_string($sort) && array_key_exists($sort, LoadoutBrowseQuery::SORTS) ? $sort : LoadoutBrowseQuery::DEFAULT_SORT;
        $classId = $request->query('class');
        $classId = is_scalar($classId) && ctype_digit((string) $classId) && $classCounts->has((int) $classId) ? (int) $classId : null;

        $loadouts = (new LoadoutBrowseQuery([], $sort))
            ->applySort($this->loadoutRows(clone $profileLoadouts)->when($classId, fn (Builder $query) => $query->where('character_id', $classId)))
            ->paginate(20)
            ->appends(array_filter([
                'class' => $classId,
                'sort' => $sort === LoadoutBrowseQuery::DEFAULT_SORT ? null : $sort,
            ]));
        $loadouts->getCollection()->each->setRelation('creator', $user);

        $showTopLoadouts = $loadouts->onFirstPage() && $classId === null && $sort !== 'salutes' && $loadoutCount > 3;
        $topLoadouts = $showTopLoadouts
            ? $this->loadoutRows(clone $profileLoadouts)
                ->orderByDesc('votes_sum_value')
                ->orderByDesc('loadouts.id')
                ->take(3)
                ->get()
                ->filter(fn (Loadout $loadout) => $loadout->votes_sum_value > 0)
                ->each->setRelation('creator', $user)
            : collect();

        $stats = [
            'loadouts' => $loadoutCount,
            'salutes' => $loadoutCount === 0 ? 0 : (int) Rating::query()
                ->where('rateable_type', (new Loadout)->getMorphClass())
                ->whereIn('rateable_id', (clone $profileLoadouts)->select('id'))
                ->sum('value'),
            'currentPatch' => $currentPatchId === null || $loadoutCount === 0
                ? 0
                : (clone $profileLoadouts)->where('patch_id', $currentPatchId)->count(),
        ];

        $this->generateSeo($user, $stats, $loadouts->currentPage());

        return view('profile.show', [
            'user' => $user,
            'isOwner' => $isOwner,
            'stats' => $stats,
            'loadouts' => $loadouts,
            'topLoadouts' => $topLoadouts,
            'classTabs' => $this->classTabs($classCounts),
            'classId' => $classId,
            'sort' => $sort,
            'currentPatchId' => $currentPatchId,
        ]);
    }

    /**
     * The user's loadouts with everything a loadout row renders, in one eager-loaded query.
     *
     * @param  Builder<Loadout>  $query
     * @return Builder<Loadout>
     */
    private function loadoutRows(Builder $query): Builder
    {
        return $query
            ->select(['loadouts.id', 'loadouts.name', 'user_id', 'character_id', 'patch_id', 'loadouts.created_at', 'loadouts.updated_at'])
            ->with('mods.gun', 'overclocks.gun', 'character', 'patch')
            ->withSum('votes', 'value');
    }

    /**
     * Class filter tabs for the classes this user has built for.
     *
     * @param  Collection<int, int>  $classCounts
     * @return list<array{id: int, name: string, count: int, accent: string}>
     */
    private function classTabs(Collection $classCounts): array
    {
        if ($classCounts->count() < 2) {
            return [];
        }

        return Character::whereIn('id', $classCounts->keys())
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Character $character) => [
                'id' => $character->id,
                'name' => $character->name,
                'count' => $classCounts[$character->id],
                'accent' => self::CLASS_ACCENTS[$character->name] ?? '#ffb547',
            ])
            ->all();
    }

    /**
     * @param  array{loadouts: int, salutes: int, currentPatch: int}  $stats
     */
    private function generateSeo(User $user, array $stats, int $page): void
    {
        $loadouts = number_format($stats['loadouts']).' Deep Rock Galactic '.Str::plural('loadout', $stats['loadouts']);

        SEOTools::setTitle("{$user->name}'s Deep Rock Galactic Builds");
        SEOTools::setDescription("{$user->name} has shared {$loadouts} on Karl.gg, earning ".number_format($stats['salutes']).' '.Str::plural('salute', $stats['salutes']).'.');
        SEOTools::setCanonical(route('user.profile', array_filter(['id' => $user->id, 'page' => $page > 1 ? $page : null])));
        SEOTools::opengraph()->setType('profile');
        SEOTools::opengraph()->setProfile(['username' => $user->name]);

        if ($stats['loadouts'] === 0) {
            SEOTools::metatags()->setRobots('noindex, follow');
        }
    }
}
