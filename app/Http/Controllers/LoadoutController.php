<?php

namespace App\Http\Controllers;

use App\Character;
use App\Loadout;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LoadoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loadouts = Loadout::latest()->paginate(25);

        return view('loadouts.index', compact('loadouts'));
    }

    /**
     * Display a listing of user's loadouts.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function myLoadouts(Request $request)
    {
        $loadouts = $request->user()->loadouts()->latest()->paginate(25);

        return view('loadouts.index', compact('loadouts'));
    }

    /**
     * Display a listing of user's favorites.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function favorites(Request $request)
    {
        $favorites = $request->user()->favorites()->where('favoriteable_type', Loadout::class)->with('favoriteable')->paginate(10);

        return view('loadouts.favorites', compact('favorites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $characters = Character::all();

        return view('loadouts.create', compact('characters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loadout = $request->user()->builds()->create($request->except('mods'));
        $loadout->mods()->sync(Arr::flatten($request->get('mods')));

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Build $loadout
     * @return \Illuminate\Http\Response
     */
    public function show(Loadout $loadout)
    {
        return view('loadouts.show', compact('loadout'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Build $loadout
     * @return \Illuminate\Http\Response
     */
    public function edit(Loadout $loadout)
    {
        $characters = Character::all();

        return view('loadouts.edit', compact('loadout', 'characters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Build $loadout
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Loadout $loadout)
    {
        $this->authorize($loadout);

        $loadout->update($request->except('mods'));
        $loadout->mods()->sync(Arr::flatten($request->get('mods')));
        $loadout->touch();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Build $loadout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $loadout)
    {
        //
    }
}
