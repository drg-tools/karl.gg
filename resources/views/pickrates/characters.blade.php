
@extends('layouts.app')

@section('header')
    Pickrates for Patch {{ $patch->patch_title }}
@endsection

@section('content')

    @include('pickrates.partials.subnav')

    @include('pickrates.partials.search')

    <div class="grid md:grid-cols-2 gap-10">
        @foreach($entities as $index => $character)
            <x-loadout-stats
                :entity="$character"
                :comparison="$comparison[$index]"
                :patch="$patch"
                :totalLoadouts="$totalLoadouts"
                :previousTotal="$previousTotal"
                loadoutLink="{!! route('loadout.index', ['character' => $character->id, 'isCurrentPatch' => 1]) !!}"
            >
                <x-slot name="identifier">
                    <a href="{{ route('pickrates.guns', ['character' => $character->id]) }}">
                        <div class="text-center">
                            <div class="p-4">
                                <img class="w-24" src="{{ $character->potrait }}"/>
                            </div>
                            <h2 class="text-xl leading-6 font-medium text-gray-300">{{ $character->name }}</h2>
                        </div>
                    </a>
                </x-slot>
            </x-loadout-stats>
        @endforeach
    </div>

    <x-pagination :entities="$entities" />

@endsection
