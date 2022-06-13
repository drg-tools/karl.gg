<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\Patch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PickRatesController extends Controller
{
    public function classes(Request $request)
    {
        return $this->getLoadoutStats(new Character, 'pickrates.characters', $request);
    }

    public function guns(Request $request)
    {
        return $this->getLoadoutStats(new Gun, 'pickrates.guns', $request);
    }

    public function mods(Request $request)
    {
        return $this->getLoadoutStats(new Mod, 'pickrates.mods', $request);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLoadoutStats(Model $model, $view, Request $request)
    {
        // Get latest patch
        $latestPatch = Patch::current();

        // Get previous patch
        $previousPatch = Patch::previous();

        // Get number of builds per entity
        $entities = $model::withCount(['loadouts' => function (Builder $query) use ($latestPatch) {
            $query->where('patch_id', $latestPatch->id);
        }])->orderBy('id')->filter($request->all())->paginate(10);

        // Get stats to compare with
        $comparison = $model::withCount(['loadouts' => function (Builder $query) use ($previousPatch) {
            $query->where('patch_id', $previousPatch->id);
        }])->orderBy('id')->filter($request->all())->paginate(10);

        // Get total loadouts this patch
        $totalLoadouts = Loadout::wherePatchId($latestPatch->id)->count();

        // And last patch
        $previousTotal = Loadout::wherePatchId($previousPatch->id)->count();

        return view($view)
            ->with('entities', $entities)
            ->with('comparison', $comparison)
            ->with('patch', $latestPatch)
            ->with('previousTotal', $previousTotal)
            ->with('totalLoadouts', $totalLoadouts);
    }
}
