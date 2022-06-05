<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use App\Patch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PickRatesController extends Controller
{
    public function index(Request $request)
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

        return view('pickrates.index')
            ->with('characters', $characters)
            ->with('patch', $latestPatch)
            ->with('comparison', $comparison)
            ->with('previousTotal', $previousTotal)
            ->with('totalLoadouts', $totalLoadouts);
    }
}
