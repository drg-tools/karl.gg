@extends('layouts.app')

@section('header')
    Pickrates for Patch {{ $patch->patch_title }}
@endsection

@section('content')

    <div class="grid md:grid-cols-2 gap-10">
        @foreach($mods as $index => $mod)
            <x-loadout-stats
                :entity="$mod"
                :comparison="$comparison[$index]"
                :patch="$patch"
                :totalLoadouts="$totalLoadouts"
                :previousTotal="$previousTotal"
            >
                <x-slot name="identifier">
                    <div class="text-center">
                        <div class="filter invert p-4">
                            <img class="w-24" src="{{ $mod->potrait }}"/>
                        </div>
                        <h2 class="text-xl leading-6 font-medium text-gray-300">{{ $mod->mod_name }}</h2>
                    </div>
                </x-slot>
            </x-loadout-stats>
        @endforeach
    </div>

@endsection
