<?php

namespace App\Http\Controllers;

use App\Gun;
use Illuminate\Http\Request;

class GunController extends Controller
{
    public function index()
    {
        $guns = Gun::with('character')->simplePaginate();

        return view('guns.index')
            ->withGuns($guns);
    }

    public function show($id)
    {
        $gun = Gun::with('mods')->findOrFail($id);

        return view('guns.show')
            ->withGun($gun);
    }
}
