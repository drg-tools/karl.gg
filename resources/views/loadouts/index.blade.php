@extends('layouts.app')

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
                @foreach(\Request::all() as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <div>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <!-- Heroicon name: solid/mail -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" value="{{ \Request::get('search') }}" name="search" class="focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search Loadouts">
                    </div>
                </div>
            </form>
        </div>

        @include('loadouts.partials.table-v2', ['loadouts' => $loadouts])

    </div>

@endsection
