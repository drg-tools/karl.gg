@extends('layouts.app')

@section('header')
    Pickrates for Patch {{ $patch->patch_title }}
@endsection

@section('content')

    @include('pickrates.partials.search')

    <div class="grid md:grid-cols-2 gap-10">
        @foreach($entities as $index => $gun)
            <a href="{{ route('pickrates.mods', ['gun' => $gun->id]) }}">
                <x-loadout-stats
                :entity="$gun"
                :comparison="$comparison[$index]"
                :patch="$patch"
                :totalLoadouts="$totalLoadouts"
                :previousTotal="$previousTotal"
            >
                <x-slot name="identifier">
                    <div class="text-center">
                        <div class="filter invert p-4">
                            <img
                                class="w-24"
                                src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }} icon"/>
                        </div>
                        <h2 class="text-xl leading-6 font-medium text-gray-300">{{ $gun->name }}</h2>
                    </div>
                </x-slot>
            </x-loadout-stats>
            </a>
        @endforeach
    </div>

    <x-pagination :entities="$entities" />

@endsection
