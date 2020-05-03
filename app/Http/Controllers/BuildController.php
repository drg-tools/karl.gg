<?php

namespace App\Http\Controllers;

use App\Build;
use App\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $builds = Build::latest()->paginate(25);

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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \App\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function show(Build $build)
    {
        return view('builds.show', compact('build'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function edit(Build $build)
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
    public function update(Request $request, Build $build)
    {
        $this->authorize($build);

        $build->update($request->except('mods'));
        $build->mods()->sync(Arr::flatten($request->get('mods')));

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $build)
    {
        //
    }
}
