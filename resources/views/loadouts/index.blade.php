@extends('layouts.app')

@section('styles')
<!-- Include base CSS (optional) -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/base.min.css"
/>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
/>
<!-- Include Choices JavaScript (latest) -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@section('header')
    Browse for Loadouts
@endsection

@section('content')

    <div id="browse">
        <div class="flex items-center py-2 flex-wrap text-gray-300">
            <span class="mr-4 font-bold">Filter by class:</span>
            <div class="flex justify-evenly flex-auto flex-wrap">
                @foreach($characters as $character)
                    <div class="text-center w-1/2 lg:w-1/4">
                        <a class="items-center flex {{ \Request::get('character') == $character->id ? 'classFilterActive' : null}}"
                           @if(\Request::get('character') == $character->id)
                           href="{{ route('loadout.index') }}"
                           @else
                           href="{{ route('loadout.index', ['character' => $character->id]) }}"
                            @endif
                        >
                            <img src="/assets/img/{{ $character->image }}-hex.png"
                                 class=""
                                 alt="{{ $character->name }} icon"
                            />
                            <span class="align-middle">{{ $character->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="my-2">
            <form action="{{ route('loadout.index') }}" method="GET">
                @csrf
                
                @foreach(\Request::all() as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                
                <x-filter :primaries="$primaries" :secondaries="$secondaries" />
            </form>
        </div>

        @include('loadouts.partials.table', ['loadouts' => $loadouts])

    </div>

@endsection
