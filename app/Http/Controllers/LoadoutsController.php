<?php

namespace App\Http\Controllers;

use App\Character;
use App\Gun;
use App\Loadout;
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
            ->simplePaginate();
        $characters = Character::all();

        return view('loadouts.index', [
            'loadouts' => $loadouts,
            'characters' => $characters,
        ]);
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

        $gunIds = $loadout->mods->pluck('gun_id')->unique();
        $gunNames = Gun::whereIn('id', $gunIds)->pluck('name');

        $gunNames->each(function ($name) {
            SEOTools::metatags()->addKeyword([
                "{$name} build",
            ]);
        });

        SEOTools::setTitle("{$loadout->character->name} - {$loadout->name}");
        SEOTools::setDescription(Str::limit($loadout->description, 100));
        SEOTools::metatags()->addKeyword([
            "{$loadout->character->name} build", 'Deep Rock Galactic builds', 'drg builds',
        ]);

        return view('loadouts.preview')->withLoadout($loadout);
    }
}
