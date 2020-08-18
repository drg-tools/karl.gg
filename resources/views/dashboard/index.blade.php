@extends('layouts.app')

@section('title', 'Loadouts')
@section('body_class', 'body__home')

@section('content')

    <call-to-action></call-to-action>

    <div class="uppercase text-center mb-2 mt-2 whitespace-pre-line">
        <h1>Karls Advanced Remote Loadout</h1>
        <h2 class="pr-4 pl-4">lets you create custom loadout builds to share with your fellow employees.
            Get started by selecting a class above or choose a popular loadout below.</h2>
    </div>

    <div class="featuredLoadoutsContainer">
        <h1 class="uppercase">Most Popular Loadouts</h1>
        <div class="cardGroups">
            <div class="loadoutCards">
                @foreach($engiLoadouts as $loadout)
                    <x-dashboard-loadout :loadout="$loadout" />
                @endforeach
            </div>
            <div class="loadoutCards">
                @foreach($scoutLoadouts as $loadout)
                    <x-dashboard-loadout :loadout="$loadout" />
                @endforeach
            </div>
            <div class="loadoutCards">
                @foreach($drillerLoadouts as $loadout)
                    <x-dashboard-loadout :loadout="$loadout" />
                @endforeach
            </div>
            <div class="loadoutCards">
                @foreach($gunnerLoadouts as $loadout)
                    <x-dashboard-loadout :loadout="$loadout" />
                @endforeach
            </div>
        </div>
    </div>

@endsection
