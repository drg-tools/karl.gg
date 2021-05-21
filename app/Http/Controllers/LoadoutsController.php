<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Loadout;
use App\User;
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
            ->with('mods', 'mods.gun', 'character', 'creator')
            ->withCount('votes')
            ->paginate();
        $characters = Character::orderBy('name')->get();
        $primaries = Gun::where('character_slot',1)->orderBy('name')->get();
        $secondaries = Gun::where('character_slot',2)->orderBy('name')->get();

        return view('loadouts.index', [
            'loadouts' => $loadouts,
            'characters' => $characters,
            'primaries' => $primaries,
            'secondaries' => $secondaries,
        ]);
    }

    public function getUsersForSearch(Request $request)
    {
        return  User::getUsers($request);
    }

    public function build()
    {
        SEOTools::setTitle('Build a Deep Rock Galactic Loadout');

        return view('loadouts.create');
    }

    public function preview($id)
    {
        $loadout = Loadout::with('mods')
            ->findOrFail($id);

        $this->generateSeo($loadout);

        return view('loadouts.preview')->withLoadout($loadout);
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
