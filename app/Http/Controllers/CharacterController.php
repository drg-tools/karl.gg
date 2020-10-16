<?php

namespace App\Http\Controllers;

use App\Character;

class CharacterController extends Controller
{
    public function index()
    {
        $characters = Character::with('guns')->get();

        return view('characters.index')->withCharacters($characters);
    }
}
