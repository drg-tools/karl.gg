<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Str;

class LoadoutsController extends Controller
{
    public function index(Request $request)
    {
        SEOTools::setTitle('Loadouts');

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
        SEOTools::setTitle('Build a Loadout');

        return view('loadouts.create');
    }

    public function preview($id)
    {
        $loadout = Loadout::findOrFail($id);

        SEOTools::setTitle($loadout->name);
        SEOTools::setDescription(Str::limit($loadout->description, 100));
        SEOTools::metatags()->addKeyword([
            "{$loadout->character->name} build", 'Deep Rock Galactic builds', 'drg builds',
        ]);

        return view('loadouts.preview');
    }
}
