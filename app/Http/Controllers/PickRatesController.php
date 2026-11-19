<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Mod;
use App\Patch;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PickRatesController extends Controller
{
    /**
     * How long the per-patch pick counts stay cached, in seconds.
     */
    private const COUNTS_CACHE_SECONDS = 900;

    /**
     * Mods listed per page when no weapon is selected.
     */
    private const MODS_PER_PAGE = 50;

    /**
     * @var array<string, string>
     */
    public const SORTS = [
        'picks' => 'Most picked',
        'rising' => 'Biggest risers',
        'falling' => 'Biggest fallers',
    ];

    /**
     * Class identity colours shared with the dashboard, builder and loadout pages.
     *
     * @var array<string, string>
     */
    private const CLASS_ACCENTS = [
        'Driller' => '#ebc64d',
        'Engineer' => '#eb745d',
        'Gunner' => '#9cba6f',
        'Scout' => '#68aaf6',
    ];

    public function classes(Request $request): View
    {
        $classes = Character::filter($this->filterInput($request, ['search']))->orderBy('name')->get();

        return $this->report($request, 'classes', $classes, $this->classCounts(...));
    }

    public function guns(Request $request): View
    {
        $guns = Gun::filter($this->filterInput($request, ['character', 'name', 'search']))
            ->with('character')
            ->orderBy('name')
            ->get();

        return $this->report($request, 'guns', $guns, $this->gunCounts(...));
    }

    public function mods(Request $request): View
    {
        $mods = Mod::filter($this->filterInput($request, ['gun', 'mod', 'search']))
            ->with('gun.character')
            ->orderBy('mod_tier')
            ->orderBy('mod_index')
            ->get();

        return $this->report($request, 'mods', $mods, $this->modCounts(...));
    }

    /**
     * @param  callable(list<int>): array<int, array<int, int>>  $countsFor
     */
    private function report(Request $request, string $type, Collection $entities, callable $countsFor): View
    {
        $currentPatch = Patch::current();
        $previousPatch = $currentPatch ? Patch::where('id', '!=', $currentPatch->id)->latest('launched_at')->first() : null;
        $patchIds = array_values(array_filter([$currentPatch?->id, $previousPatch?->id]));

        $totals = $this->cached('totals', $patchIds, fn () => DB::table('loadouts')
            ->whereIn('patch_id', $patchIds)
            ->groupBy('patch_id')
            ->pluck(DB::raw('count(*)'), 'patch_id')
            ->map(fn ($total) => (int) $total)
            ->all());
        $counts = $this->cached($type, $patchIds, fn () => $countsFor($patchIds));
        $weaponCounts = $type === 'mods' ? $this->cached('guns', $patchIds, fn () => $this->gunCounts($patchIds)) : [];

        $sort = array_key_exists((string) $request->query('sort'), self::SORTS) ? $request->query('sort') : 'picks';
        $hasComparison = $previousPatch !== null;

        $rows = $entities
            ->map(fn ($entity) => $this->row(
                $entity,
                $counts[$entity->id] ?? [],
                $totals,
                $currentPatch,
                $previousPatch,
                $type === 'mods' ? ($weaponCounts[$entity->gun_id][$currentPatch?->id] ?? 0) : null,
            ))
            ->sortBy(match ($sort) {
                'rising' => [['delta', 'desc'], ['current', 'desc']],
                'falling' => [['delta', 'asc'], ['current', 'desc']],
                default => [['current', 'desc'], ['previous', 'desc']],
            })
            ->values();

        $paginate = $type === 'mods' && ! $request->filled('gun');
        $page = $paginate
            ? new LengthAwarePaginator(
                $rows->forPage(LengthAwarePaginator::resolveCurrentPage(), self::MODS_PER_PAGE)->values(),
                $rows->count(),
                self::MODS_PER_PAGE,
                LengthAwarePaginator::resolveCurrentPage(),
                ['path' => $request->url(), 'query' => $request->except('page')],
            )
            : null;

        return view('pickrates.report', [
            'type' => $type,
            'rows' => $page ? $page->getCollection() : $rows,
            'paginator' => $page,
            'rankOffset' => $page ? ($page->currentPage() - 1) * self::MODS_PER_PAGE : 0,
            'scaleMax' => max(1, $rows->max(fn (array $row) => max($row['rate'], $row['previousRate'])) ?? 1),
            'currentPatch' => $currentPatch,
            'previousPatch' => $previousPatch,
            'hasComparison' => $hasComparison,
            'currentTotal' => $totals[$currentPatch?->id] ?? 0,
            'previousTotal' => $totals[$previousPatch?->id] ?? 0,
            'sort' => $sort,
            'search' => is_string($request->query('search')) ? $request->query('search') : '',
            'characters' => Character::orderBy('name')->get(['id', 'name', 'image']),
            'weapons' => $type === 'mods' ? Gun::orderBy('name')->get(['id', 'name', 'character_id', 'character_slot']) : collect(),
            'selectedCharacterId' => $this->scalarId($request, 'character'),
            'selectedGunId' => $this->scalarId($request, 'gun'),
            'accents' => self::CLASS_ACCENTS,
        ]);
    }

    /**
     * @param  array<int, int>  $counts  Loadouts per patch id.
     * @param  array<int, int>  $totals  All loadouts per patch id.
     * @return array{entity: mixed, current: int, previous: int, rate: float, previousRate: float, delta: float, weaponShare: ?float}
     */
    private function row($entity, array $counts, array $totals, ?Patch $currentPatch, ?Patch $previousPatch, ?int $weaponTotal): array
    {
        $current = $counts[$currentPatch?->id] ?? 0;
        $previous = $counts[$previousPatch?->id] ?? 0;
        $rate = $this->percentage($current, $totals[$currentPatch?->id] ?? 0);
        $previousRate = $this->percentage($previous, $totals[$previousPatch?->id] ?? 0);

        return [
            'entity' => $entity,
            'current' => $current,
            'previous' => $previous,
            'rate' => $rate,
            'previousRate' => $previousRate,
            'delta' => $previousPatch ? round($rate - $previousRate, 1) : 0.0,
            'weaponShare' => $weaponTotal === null ? null : $this->percentage($current, $weaponTotal),
        ];
    }

    private function percentage(int $count, int $total): float
    {
        return $total > 0 ? round($count / $total * 100, 1) : 0.0;
    }

    /**
     * @param  list<int>  $patchIds
     * @return array<int, array<int, int>>
     */
    private function classCounts(array $patchIds): array
    {
        return $this->groupCounts(DB::table('loadouts')
            ->whereIn('patch_id', $patchIds)
            ->groupBy('character_id', 'patch_id')
            ->selectRaw('character_id as entity_id, patch_id, count(*) as total'));
    }

    /**
     * Loadouts with at least one mod on each weapon, matching the gun_loadout view.
     *
     * @param  list<int>  $patchIds
     * @return array<int, array<int, int>>
     */
    private function gunCounts(array $patchIds): array
    {
        return $this->groupCounts(DB::table('loadout_mod')
            ->join('mods', 'mods.id', '=', 'loadout_mod.mod_id')
            ->join('loadouts', 'loadouts.id', '=', 'loadout_mod.loadout_id')
            ->whereIn('loadouts.patch_id', $patchIds)
            ->groupBy('mods.gun_id', 'loadouts.patch_id')
            ->selectRaw('mods.gun_id as entity_id, loadouts.patch_id, count(distinct loadout_mod.loadout_id) as total'));
    }

    /**
     * @param  list<int>  $patchIds
     * @return array<int, array<int, int>>
     */
    private function modCounts(array $patchIds): array
    {
        return $this->groupCounts(DB::table('loadout_mod')
            ->join('loadouts', 'loadouts.id', '=', 'loadout_mod.loadout_id')
            ->whereIn('loadouts.patch_id', $patchIds)
            ->groupBy('loadout_mod.mod_id', 'loadouts.patch_id')
            ->selectRaw('loadout_mod.mod_id as entity_id, loadouts.patch_id, count(*) as total'));
    }

    /**
     * @return array<int, array<int, int>>
     */
    private function groupCounts(QueryBuilder $query): array
    {
        $counts = [];

        foreach ($query->get() as $row) {
            $counts[(int) $row->entity_id][(int) $row->patch_id] = (int) $row->total;
        }

        return $counts;
    }

    /**
     * @param  list<int>  $patchIds
     */
    private function cached(string $key, array $patchIds, callable $callback): array
    {
        return $patchIds === []
            ? []
            : Cache::remember("pickrates.{$key}.".implode('-', $patchIds), self::COUNTS_CACHE_SECONDS, $callback);
    }

    /**
     * @param  list<string>  $keys
     * @return array<string, string>
     */
    private function filterInput(Request $request, array $keys): array
    {
        return array_filter($request->only($keys), fn ($value) => is_scalar($value) && trim((string) $value) !== '');
    }

    private function scalarId(Request $request, string $key): ?int
    {
        $value = $request->query($key);

        return is_scalar($value) && ctype_digit((string) $value) ? (int) $value : null;
    }
}
