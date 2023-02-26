@extends('layouts.app')

@section('header')
    Pickrates for Patch {{ $patch->patch_title }}
@endsection

@section('content')

    @include('pickrates.partials.subnav')

    @include('pickrates.partials.search')

    <div class="grid md:grid-cols-2 gap-10">
        @foreach($entities as $index => $gun)

            @php
                if ($gun->character_slot === 1) {
                    $route = route('loadout.index', ['primaries' => [$gun->id], 'isCurrentPatch' => 1]);
                } else {
                    $route = route('loadout.index', ['secondaries' => [$gun->id], 'isCurrentPatch' => 1]);
                }
            @endphp

            <x-loadout-stats
                :entity="$gun"
                :comparison="$comparison[$index]"
                :patch="$patch"
                :totalLoadouts="$totalLoadouts"
                :previousTotal="$previousTotal"
                loadoutLink=" {!! $route !!}"
            >
                <x-slot name="identifier">
                    <a href="{{ route('pickrates.mods', ['gun' => $gun->id]) }}">
                        <div class="text-center">
                            <div class="filter invert p-4">
                                <img
                                    class="w-24"
                                    src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }} icon"/>
                            </div>
                            <h2 class="text-xl leading-6 font-medium text-gray-300">{{ $gun->name }}</h2>
                        </div>
                    </a>
                </x-slot>
            </x-loadout-stats>
        @endforeach
    </div>

    <x-pagination :entities="$entities" />

@endsection
