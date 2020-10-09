@extends('layouts.app')

@section('content')

    <div id="browse">
        <div class="classFilterContainer flex items-center py-2 flex-wrap">
            <span class="mr-4 font-bold">Filter by class:</span>
            <div class="flex justify-evenly flex-auto flex-wrap">
                @foreach($characters as $character)
                    <div class="text-center w-1/2 lg:w-1/4">
                        <a class="classFilter items-center flex {{ \Request::get('character') == $character->id ? 'classFilterActive' : null}}"
                           @if(\Request::get('character') == $character->id)
                           href="{{ route('loadout.index') }}"
                           @else
                           href="{{ route('loadout.index', ['character' => $character->id]) }}"
                            @endif
                        >
                            <img src="/assets/img/{{ $character->image }}-hex.png"
                                 class="classIcon"
                                 alt="{{ $character->name }} icon"
                            />
                            <span class="align-middle">{{ $character->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="searchContainer">
            <form action="{{ route('loadout.index') }}" method="GET">
                <span><i class="fas fa-search"></i></span>
                @foreach(\Request::all() as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <input type="search" value="{{ \Request::get('search') }}" name="search" placeholder="Search Loadouts">
            </form>
        </div>

        <div class="w-full relative overflow-x-auto">
            <table class="table w-full table-auto">
                <thead>
                <tr>
                    <th>@sortablelink('character_id', 'Class')</th>
                    <th>@sortablelink('name')</th>
                    <th>@sortablelink('creator.name', 'Author')</th>
                    <th>Primary</th>
                    <th>Secondary</th>
                    <th>@sortablelink('votes_count', 'Salutes')</th>
                    <th>@sortablelink('updated_at', 'Last Update')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($loadouts as $loadout)
                    <tr onclick="window.open('{{ route('preview.show', $loadout->id) }}','_blank')">
                        <td><img src="/assets/img/{{ $loadout->character->image }}-hex.png" alt="{{ $loadout->character->name }} icon"></td>
                        <td class="text-xl">{{ Str::limit($loadout->name, 50) }}</td>
                        <td class="text-xl">{{ $loadout->creator->name ?? 'Anonymous' }}</td>
                        <td class="weapon">
                            @if($loadout->primaryGun)
                                <img src="/assets/{{ $loadout->primaryGun->image }}.svg" alt="{{ $loadout->primaryGun->name }} icon">
                            @endif
                        </td>
                        <td class="weapon">
                            @if($loadout->secondaryGun)
                                <img src="/assets/{{ $loadout->secondaryGun->image }}.svg" alt="{{ $loadout->secondaryGun->name }} icon">
                            @endif
                        </td>
                        <td class="text-right">{{ $loadout->votes_count }}</td>
                        <td class="text-sm">{{ $loadout->updated_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-2 mb-2 text-lg">
            {{ $loadouts->withQueryString()->render() }}
        </div>
    </div>

@endsection
