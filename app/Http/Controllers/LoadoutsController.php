<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use Illuminate\Http\Request;

class LoadoutsController extends Controller
{
    public function index(Request $request)
    {
        $loadouts = Loadout::sortable(['votes'])
            ->filter($request->all())
            ->with('mods', 'mods.gun', 'character', 'creator')
            ->paginate();
        $characters = Character::all();

        return view('loadouts.index', [
            'loadouts' => $loadouts,
            'characters' => $characters
        ]);
    }
}
