<?php

namespace App\Http\Controllers;

use App\BuildMetric;
use App\Character;
use App\Http\Requests\SaveLoadoutRequest;
use App\Http\Resources\BuilderCatalogResource;
use App\Loadout;
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
            ] : null,
            'saveUrl' => $canUpdate ? route('builder.update', $loadout) : route('builder.store'),
            'method' => $canUpdate ? 'PUT' : 'POST',
            'signedIn' => $request->user() !== null,
            'metricsUrl' => route('builder.metrics'),
            'loginUrl' => route('login'),
        ]]);
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
