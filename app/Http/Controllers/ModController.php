<?php

namespace App\Http\Controllers;

use App\Mod;

class ModController extends Controller
{
    public function index()
    {
        $mods = Mod::with('gun')->simplePaginate();

        return view('mods.index')->withMods($mods);
    }

    public function show($id)
    {
        $mod = Mod::findOrFail($id);

        return view('mods.show')->withMod($mod);
    }
}
