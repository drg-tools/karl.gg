<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use App\Character;
use App\EquipmentMod;
use App\Gun;
use App\Http\Requests\SaveLoadoutRequest;
use App\Http\Resources\BuilderCatalogResource;
use App\Loadout;
use App\Mod;
use App\SaveLoadout;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuilderController extends Controller
{
    public function create(Request $request, ?int $loadoutId = null): View
    {
        $loadout = $loadoutId ? Loadout::with('mods', 'overclocks', 'equipment_mods')->findOrFail($loadoutId) : null;
        $canUpdate = $loadout && ($request->user()?->can('update', $loadout) ?? false);
        $handoff = $loadout ? null : $this->calculatorHandoff($request);
        SEOTools::setTitle('Build a Deep Rock Galactic Loadout');

        return view('loadouts.create', ['builder' => [
            'characters' => Character::orderBy('name')->get()->map(fn (Character $character): array => [
                'id' => $character->id, 'name' => $character->name,
                'url' => route('builder.catalog', $character),
            ]),
            'initial' => $loadout ? [
                'name' => $loadout->name, 'description' => $loadout->description,
                'character_id' => $loadout->character_id, 'throwable_id' => $loadout->throwable_id,
                'mods' => $loadout->mods->modelKeys(), 'overclocks' => $loadout->overclocks->modelKeys(),
                'equipment_mods' => $loadout->equipment_mods->modelKeys(),
            ] : $handoff['initial'] ?? null,
            'weapons' => $handoff['weapons'] ?? null,
            'saveUrl' => $canUpdate ? route('builder.update', $loadout) : route('builder.store'),
            'method' => $canUpdate ? 'PUT' : 'POST',
            'signedIn' => $request->user() !== null,
            'metricsUrl' => route('builder.metrics'),
            'loginUrl' => route('login'),
        ]]);
    }

    /**
     * Picks handed over from the build calculator's "Use in builder" link.
     * Only ids that belong to the class are kept, with at most one weapon per
     * slot, one upgrade per tier and one overclock per weapon; the lowest id
     * wins a clash.
     *
     * @return array{initial: array{character_id: int, throwable_id: ?int, mods: list<int>, overclocks: list<int>, equipment_mods: list<int>}, weapons: array<int, int>}|null
     */
    private function calculatorHandoff(Request $request): ?array
    {
        $character = $request->filled('class') ? Character::find($request->integer('class')) : null;
        if (! $character) {
            return null;
        }

        $ids = function (string $key) use ($request): array {
            $value = $request->query($key);

            return is_string($value) ? array_values(array_filter(array_map('intval', explode(',', $value)))) : [];
        };

        $guns = $character->guns()->whereIn('id', $ids('guns'))->orderBy('id')->get()->unique('character_slot');
        $gunIds = $guns->modelKeys();
        $mods = $character->mods()->whereIn('gun_id', $gunIds)->whereIn('id', $ids('mods'))->orderBy('id')->get()
            ->unique(fn (Mod $mod): string => $mod->gun_id.':'.$mod->mod_tier);
        $overclocks = $character->overclocks()->whereIn('gun_id', $gunIds)->whereIn('id', $ids('overclocks'))->orderBy('id')->get()
            ->unique('gun_id');
        $equipmentMods = $character->equipment_mods()->whereIn('id', $ids('equipment_mods'))->orderBy('id')->get()
            ->unique(fn (EquipmentMod $mod): string => $mod->equipment_id.':'.$mod->mod_tier);

        return [
            'initial' => [
                'character_id' => $character->id,
                'throwable_id' => $request->filled('throwable')
                    ? $character->throwables()->whereKey($request->integer('throwable'))->value('id')
                    : null,
                'mods' => $mods->modelKeys(),
                'overclocks' => $overclocks->modelKeys(),
                'equipment_mods' => $equipmentMods->modelKeys(),
            ],
            'weapons' => $guns->mapWithKeys(fn (Gun $gun): array => [$gun->character_slot => $gun->id])->all(),
        ];
    }

    public function catalog(Character $character): BuilderCatalogResource
    {
        return new BuilderCatalogResource($character->load('guns.mods', 'guns.overclocks', 'equipments.equipment_mods', 'throwables'));
    }

    public function store(SaveLoadoutRequest $request, SaveLoadout $save): JsonResponse
    {
        $loadout = $save->handle($request->validated(), $request->user());

        return response()->json(['id' => $loadout->id, 'url' => route('preview.show', $loadout->id)], 201);
    }

    public function update(SaveLoadoutRequest $request, Loadout $loadout, SaveLoadout $save): JsonResponse
    {
        $save->handle($request->validated(), $request->user(), $loadout);

        return response()->json(['id' => $loadout->id, 'url' => route('preview.show', $loadout->id)]);
    }

    public function metrics(Request $request): JsonResponse
    {
        $input = $request->validate(['gun_id' => ['required', 'integer', 'exists:guns,id'], 'combo' => ['required', 'string', 'regex:/^[ABC123\-]{5}[0-9\-]$/']]);
        $metric = BuildMetric::where('gun_id', $input['gun_id'])
            ->where('build_combination', BuildMetric::normalizeCombination($input['combo']))
            ->first();

        return response()->json(['data' => $metric]);
    }
}
