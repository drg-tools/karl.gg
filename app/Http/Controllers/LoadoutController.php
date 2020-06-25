<?php

namespace App\Http\Controllers;

use App\Loadout;
use App\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LoadoutController extends Controller
{
    // TODO: Fix naming conventions for Loadout Terminology


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Loadouts = Loadout::latest()->paginate(25);

        return view('builds.index', compact('builds'));
    }

    /**
     * Display a listing of user's builds.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function myBuilds(Request $request)
    {
        $builds = $request->user()->builds()->latest()->paginate(25);

        return view('builds.index', compact('builds'));
    }

    /**
     * Display a listing of user's favorites.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function favorites(Request $request)
    {
        $favorites = $request->user()->favorites()->where('favoriteable_type', Build::class)->with('favoriteable')->paginate(10);

        return view('builds.favorites', compact('favorites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $characters = Character::all();

        return view('builds.create', compact('characters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $build = $request->user()->builds()->create($request->except('mods'));
        $build->mods()->sync(Arr::flatten($request->get('mods')));

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Build $build
     * @return \Illuminate\Http\Response
     */
    public function show(Loadout $build)
    {
        return view('builds.show', compact('build'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Build $build
     * @return \Illuminate\Http\Response
     */
    public function edit(Loadout $build)
    {
        $characters = Character::all();

        return view('builds.edit', compact('build', 'characters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Build $build
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Loadout $build)
    {
        $this->authorize($build);

        $build->update($request->except('mods'));
        $build->mods()->sync(Arr::flatten($request->get('mods')));
        $build->touch();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Build $build
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $build)
    {
        //
    }
}
