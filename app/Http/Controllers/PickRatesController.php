<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\Patch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PickRatesController extends Controller
{
    public function classes(Request $request)
    {
        // Get latest patch
        $latestPatch = Patch::current();

        // Get previous patch
        $previousPatch = Patch::previous();

        // Get number of builds per class
        $characters = Character::withCount(['loadouts' => function (Builder $query) use ($latestPatch) {
            $query->where('patch_id', $latestPatch->id);
        }])->orderBy('id')->get();

        // Get stats to compare with
        $comparison = Character::withCount(['loadouts' => function (Builder $query) use ($previousPatch) {
            $query->where('patch_id', $previousPatch->id);
        }])->orderBy('id')->get();

        // Get total loadouts this patch
        $totalLoadouts = Loadout::wherePatchId($latestPatch->id)->count();

        // And last patch
        $previousTotal = Loadout::wherePatchId($previousPatch->id)->count();

        return view('pickrates.characters')
            ->with('characters', $characters)
            ->with('patch', $latestPatch)
            ->with('comparison', $comparison)
            ->with('previousTotal', $previousTotal)
            ->with('totalLoadouts', $totalLoadouts);
    }

    public function mods(Request $request)
    {
        // Get latest patch
        $latestPatch = Patch::current();

        // Get previous patch
        $previousPatch = Patch::previous();

        // Get number of builds per class
        $mods = Mod::withCount(['loadouts' => function (Builder $query) use ($latestPatch) {
            $query->where('patch_id', $latestPatch->id);
        }])->orderBy('id')->get();

        // Get stats to compare with
        $comparison = Mod::withCount(['loadouts' => function (Builder $query) use ($previousPatch) {
            $query->where('patch_id', $previousPatch->id);
        }])->orderBy('id')->get();

        // Get total loadouts this patch
        $totalLoadouts = Loadout::wherePatchId($latestPatch->id)->count();

        // And last patch
        $previousTotal = Loadout::wherePatchId($previousPatch->id)->count();

        return view('pickrates.mods')
            ->with('mods', $mods)
            ->with('patch', $latestPatch)
            ->with('comparison', $comparison)
            ->with('previousTotal', $previousTotal)
            ->with('totalLoadouts', $totalLoadouts);
    }
}
