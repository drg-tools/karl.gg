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

    <x-dashboard-listing :loadoutList="$recentTopLoadouts" :title="'Top Loadouts in Past 2 Weeks'" />
    <x-dashboard-listing :loadoutList="$latestLoadouts" :title="'Newest Loadouts'" />
    <x-dashboard-listing :loadoutList="$allTimeTopLoadouts" :title="'Most Popular Loadouts -- All Time'" />

@endsection
