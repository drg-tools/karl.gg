@extends('layouts.app')

@section('content')

    <div class="content-container">
        <div class="border-b mx-4">
            <div class="my-4 border-b-4 flex content-start items-center">
                <x-gun-image :gun="$gun" class="w-16"/>
                <h2 class="ml-4">{{ $gun->name }}</h2>
            </div>

            <h3>Available Mods</h3>
            @foreach($gun->mods->groupBy('mod_tier') as $tier_id => $tier)
                <h4>Tier {{ $tier_id }}</h4>
                <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tier as $mod)
                        <div class="p-4">
                            <h4><a href="{{ route('mods.show', $mod->id) }}">{{ $mod->name }}</a></h4>
                            <x-mod-image :mod="$mod" class="w-16"/>
                            <p>{{ $mod->text_description }}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endsection
