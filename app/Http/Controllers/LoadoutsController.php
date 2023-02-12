<?php

namespace App\Http\Controllers;

use App\Character;
use App\Equipment;
use App\Gun;
use App\Loadout;
use App\Overclock;
use App\Throwable;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Str;

class LoadoutsController extends Controller
{
    public function index(Request $request)
    {
        SEOTools::setTitle('Deep Rock Galactic Loadouts');
        $loadouts = Loadout::sortable(['updated_at' => 'desc'])
            ->filter($request->all())
            ->select(['loadouts.id', 'loadouts.name', 'user_id', 'character_id', 'patch_id', 'throwable_id', 'loadouts.created_at', 'loadouts.updated_at'])
            ->with('mods', 'mods.gun', 'character', 'creator', 'patch')
            ->withSum('votes', 'value')
            ->paginate();

        $characters = Character::orderBy('name')->pluck('name', 'id');
        $overclocks = Overclock::orderBy('overclock_name')->with('character')->get();
        $primaries = Gun::where('character_slot', 1)->orderBy('name')->with('character')->get();
        $secondaries = Gun::where('character_slot', 2)->orderBy('name')->with('character')->get();
        $throwables = Throwable::orderBy('name')->pluck('name', 'id');

        return view('loadouts.index', [
            'loadouts' => $loadouts,
            'characters' => $characters,
            'primaries' => $primaries,
            'secondaries' => $secondaries,
            'overclocks' => $overclocks,
            'throwables' => $throwables,
        ]);
    }

    public function build()
    {
        SEOTools::setTitle('Build a Deep Rock Galactic Loadout');

        return view('loadouts.create');
    }

    public function preview($id)
    {
        $loadout = Loadout::with('mods', 'equipment_mods', 'overclocks')
            ->findOrFail($id);

        $availableEquipment = Equipment::where('character_id', $loadout->character_id)
            ->with('equipment_mods')
            ->get();

        $this->generateSeo($loadout);

        return view('loadouts.preview')
            ->withLoadout($loadout)
            ->withAvailableEquipment($availableEquipment);
    }

    public function delete(Request $request, $id)
    {
        $loadout = Loadout::findOrFail($id);

        if ($request->user()->cannot('delete', $loadout)) {
            abort(403);
        }

        $loadout->delete();

        // Currently you can only delete from user profile, so redirect there
        return redirect('profile/'.$request->user()->id);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFavorite(Request $request, $id)
    {
        $loadout = Loadout::findOrFail($id);

        $loadout->toggleFavorite();

        return redirect()->back();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection|null  $loadout
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
}
