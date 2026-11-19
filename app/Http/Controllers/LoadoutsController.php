<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Loadout;
use App\LoadoutBrowseQuery;
use App\Mod;
use App\Overclock;
use App\Patch;
use App\Throwable;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection as SupportCollection;
use Str;

class LoadoutsController extends Controller
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

    public function index(Request $request): Response
    {
        SEOTools::setTitle('Deep Rock Galactic Loadouts');

        $browse = LoadoutBrowseQuery::fromRequest($request);

        $loadouts = $browse->applySort(
            Loadout::filter($browse->filters)
                ->select(['loadouts.id', 'loadouts.name', 'user_id', 'character_id', 'patch_id', 'throwable_id', 'loadouts.created_at', 'loadouts.updated_at'])
                ->with('mods.gun', 'overclocks.gun', 'character', 'creator', 'patch')
                ->withSum('votes', 'value')
        )
            ->paginate(20)
            ->appends($browse->query());

        $catalog = $this->browseCatalog();

        $data = [
            'browse' => $browse,
            'loadouts' => $loadouts,
            'activeFilters' => $this->activeFilters($browse, $catalog),
            'currentPatchId' => Patch::current()?->id,
        ];

        // Live filtering swaps in just the results fragment.
        $view = $request->ajax()
            ? view('loadouts.partials.results', $data)
            : view('loadouts.index', $data + [
                'catalog' => $catalog,
                'pickers' => $this->pickerGroups($catalog),
                'accents' => self::CLASS_ACCENTS,
            ]);

        return response($view)->header('Vary', 'X-Requested-With');
    }

    public function preview($id)
    {
        $loadout = Loadout::with('mods.gun.mods', 'equipment_mods.equipment.equipment_mods', 'overclocks.gun.mods', 'throwable', 'character', 'creator', 'patch')
            ->findOrFail($id);

        $this->generateSeo($loadout);

        return view('loadouts.preview')
            ->withLoadout($loadout)
            ->withIsCurrentPatch($loadout->patch?->is(Patch::current()) ?? false)
            ->withHasVoted(auth()->check() && auth()->user()->getVotingValue($loadout) == 1)
            ->withVoteCount($loadout->upVotesCount())
            ->withGuideHtml(Str::markdown($loadout->description ?? '', ['html_input' => 'strip', 'allow_unsafe_links' => false]));
    }

    public function delete(Request $request, $id)
    {
        $loadout = Loadout::findOrFail($id);

        if ($request->user()->cannot('delete', $loadout)) {
            abort(403);
        }

        $loadout->delete();

        // Loadouts are deleted from the owner's profile, so return there.
        return redirect()
            ->route('user.profile', $request->user()->id)
            ->with('status', "Deleted \"{$loadout->name}\".");
    }

    /**
     * @return RedirectResponse
     */
    public function toggleFavorite(Request $request, $id)
    {
        $loadout = Loadout::findOrFail($id);

        $loadout->toggleFavorite();

        return redirect()->back();
    }

    /**
     * @param  Collection|null  $loadout
     */
    protected function generateSeo($loadout): void
    {
        $gunIds = $loadout->mods->pluck('gun_id')->unique();
        $gunNames = Gun::whereIn('id', $gunIds)->pluck('name');

        $gunNames->each(function ($name) {
            SEOTools::metatags()->addKeyword([
                // Remove quotes from gun names
                str_replace('"', '', "{$name} build"),
            ]);
        });
        $loadout->mods->each(function ($l) {
            SEOTools::metatags()->addKeyword([
                // Remove quotes from mod names
                str_replace('"', '', "{$l->mod_name} build"),
            ]);
        });

        $modDescription = $loadout->mods->isEmpty() ? 'no mods' : $loadout->mods->pluck('mod_name')->join(' mod, ');
        $description = "{$loadout->character->name} build with {$modDescription}";

        SEOTools::setTitle("{$loadout->character->name} - {$loadout->name}");
        SEOTools::setDescription(Str::limit($description, 150));
        SEOTools::metatags()->addKeyword([
            "{$loadout->character->name} build", 'Deep Rock Galactic builds', 'drg builds',
        ]);
    }

    /**
     * Everything the filter pickers can offer, loaded once per request.
     *
     * @return array{characters: Collection<int, Character>, guns: Collection<int, Gun>, overclocks: Collection<int, Overclock>, mods: Collection<int, Mod>, throwables: Collection<int, Throwable>}
     */
    private function browseCatalog(): array
    {
        $characters = Character::orderBy('name')->get(['id', 'name', 'image']);
        $classOrder = $characters->pluck('id')->flip();

        return [
            'characters' => $characters,
            'guns' => Gun::get(['id', 'name', 'character_id', 'character_slot', 'image'])
                ->sortBy([
                    fn (Gun $a, Gun $b) => ($classOrder[$a->character_id] ?? 99) <=> ($classOrder[$b->character_id] ?? 99),
                    ['character_slot', 'asc'],
                    ['name', 'asc'],
                ])
                ->values(),
            'overclocks' => Overclock::orderBy('overclock_index')->get(['id', 'character_id', 'gun_id', 'overclock_name', 'overclock_type']),
            'mods' => Mod::orderBy('mod_tier')->orderBy('mod_index')->get(['id', 'character_id', 'gun_id', 'mod_tier', 'mod_index', 'mod_name']),
            'throwables' => Throwable::orderBy('name')->get(['id', 'character_id', 'name']),
        ];
    }

    /**
     * Option groups for each picker: weapons and throwables grouped by class,
     * overclocks and mods grouped by weapon.
     *
     * @param  array{characters: Collection<int, Character>, guns: Collection<int, Gun>, overclocks: Collection<int, Overclock>, mods: Collection<int, Mod>, throwables: Collection<int, Throwable>}  $catalog
     * @return array<string, list<array{label: string, classId: int, accent: string, options: list<array{id: int, name: string, meta: ?string}>}>>
     */
    private function pickerGroups(array $catalog): array
    {
        $byClass = fn (SupportCollection $items, string $nameKey) => $catalog['characters']
            ->map(fn (Character $character) => [
                'label' => $character->name,
                'classId' => $character->id,
                'accent' => self::CLASS_ACCENTS[$character->name] ?? '#ffb547',
                'options' => $items->where('character_id', $character->id)
                    ->sortBy($nameKey)
                    ->map(fn ($item) => ['id' => $item->id, 'name' => $item->{$nameKey}, 'meta' => null])
                    ->values()
                    ->all(),
            ])
            ->filter(fn (array $group) => $group['options'] !== [])
            ->values()
            ->all();

        $characterNames = $catalog['characters']->pluck('name', 'id');
        $byGun = fn (SupportCollection $items, callable $option) => $catalog['guns']
            ->map(fn (Gun $gun) => [
                'label' => $gun->name,
                'classId' => $gun->character_id,
                'accent' => self::CLASS_ACCENTS[$characterNames[$gun->character_id] ?? ''] ?? '#ffb547',
                'options' => $items->where('gun_id', $gun->id)->map($option)->values()->all(),
            ])
            ->filter(fn (array $group) => $group['options'] !== [])
            ->values()
            ->all();

        return [
            'primaries' => $byClass($catalog['guns']->where('character_slot', 1), 'name'),
            'secondaries' => $byClass($catalog['guns']->where('character_slot', 2), 'name'),
            'overclocks' => $byGun($catalog['overclocks'], fn (Overclock $overclock) => [
                'id' => $overclock->id,
                'name' => $overclock->overclock_name,
                'meta' => $overclock->overclock_type,
            ]),
            'mods' => $byGun($catalog['mods'], fn (Mod $mod) => [
                'id' => $mod->id,
                'name' => $mod->mod_name,
                'meta' => "T{$mod->mod_tier}{$mod->mod_index}",
            ]),
            'throwables' => $byClass($catalog['throwables'], 'name'),
        ];
    }

    /**
     * Removable chips for every active filter, each linking to the same results without it.
     *
     * @param  array{characters: Collection<int, Character>, guns: Collection<int, Gun>, overclocks: Collection<int, Overclock>, mods: Collection<int, Mod>, throwables: Collection<int, Throwable>}  $catalog
     * @return list<array{label: string, url: string}>
     */
    private function activeFilters(LoadoutBrowseQuery $browse, array $catalog): array
    {
        $names = [
            'characters' => ['Class', $catalog['characters']->pluck('name', 'id')],
            'primaries' => ['Primary', $catalog['guns']->pluck('name', 'id')],
            'secondaries' => ['Secondary', $catalog['guns']->pluck('name', 'id')],
            'overclocks' => ['Overclock', $catalog['overclocks']->pluck('overclock_name', 'id')],
            'mods' => ['Mod', $catalog['mods']->pluck('mod_name', 'id')],
            'throwables' => ['Throwable', $catalog['throwables']->pluck('name', 'id')],
        ];

        $chips = [];

        if ($browse->has('search')) {
            $chips[] = ['label' => 'Name: “'.$browse->filters['search'].'”', 'url' => $browse->urlWithout('search')];
        }

        foreach ($names as $key => [$prefix, $lookup]) {
            foreach ($browse->ids($key) as $id) {
                $chips[] = ['label' => $prefix.': '.($lookup[$id] ?? "#{$id}"), 'url' => $browse->urlWithout($key, $id)];
            }
        }

        $toggles = [
            'hasOverclock' => ['Yes' => 'Has overclock', 'No' => 'No overclock'],
            'hasGuide' => ['Yes' => 'Has guide', 'No' => 'No guide'],
            'isCurrentPatch' => [1 => 'Current patch'],
            'favorites' => [1 => 'My favorites'],
        ];

        foreach ($toggles as $key => $labels) {
            if ($browse->has($key)) {
                $chips[] = ['label' => $labels[$browse->filters[$key]], 'url' => $browse->urlWithout($key)];
            }
        }

        return $chips;
    }
}
