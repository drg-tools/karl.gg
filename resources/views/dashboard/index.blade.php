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
        <h1 class="uppercase text-center">Top Loadouts in Past 2 Weeks</h1>
        <div class="cardGroups flex flex-wrap mb-4 -mx-1">
            @foreach($recentTopLoadouts as $characterLoadouts)
            <div class="loadoutCards w-full lg:w-1/2 px-1">
                @foreach($characterLoadouts as $loadout)
                <x-dashboard-loadout :loadout="$loadout"/>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    <div class="featuredLoadoutsContainer">
        <h1 class="uppercase text-center">Newest Loadouts</h1>
        <div class="cardGroups flex flex-wrap mb-4 -mx-1">
            @foreach($latestLoadouts as $characterLoadouts)
            <div class="loadoutCards w-full lg:w-1/2 px-1">
                @foreach($characterLoadouts as $loadout)
                <x-dashboard-loadout :loadout="$loadout"/>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <div class="featuredLoadoutsContainer">
        <h1 class="uppercase text-center">Most Popular Loadouts -- All Time</h1>
        <div class="cardGroups flex flex-wrap mb-4 -mx-1">
            @foreach($allTimeTopLoadouts as $characterLoadouts)
            <div class="loadoutCards w-full lg:w-1/2 px-1">
                @foreach($characterLoadouts as $loadout)
                <x-dashboard-loadout :loadout="$loadout"/>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

@endsection
