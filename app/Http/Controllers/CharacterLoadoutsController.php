<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;

class CharacterLoadoutsController extends Controller
{
    public function index()
    {
        $characters = Character::with('guns')->get();

        // TODO: implement recommended loadouts per character
        $loadouts = Loadout::limit(4)->get();

        return view('character-loadouts.index')
            ->withLoadouts($loadouts)
            ->withCharacters($characters);
    }
}
