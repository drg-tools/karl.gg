@extends('layouts.app')

@section('title', 'Browse')

@section('content')

    <div class="tableContainer">
        <div class="classFilterContainer">
            <h1>Filter by class:</h1>
            @foreach($characters as $character)
                <a class="classFilter {{ \Request::get('character') == $character->id ? 'classFilterActive' : null}}"
                   href="{{ route('loadout.index', ['character' => $character->id]) }}">
                    <img src="/assets/img/{{ $character->image }}-hex.png"
                         class="classIcon"/>
                    <h2>{{ $character->name }}</h2>
                </a>
            @endforeach
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>@sortablelink('character_id', 'Class')</th>
                <th>@sortablelink('name')</th>
                <th>@sortablelink('creator.name', 'Author')</th>
                <th>Primary</th>
                <th>Secondary</th>
                <th>@sortablelink('votes', 'Salutes')</th>
                <th>@sortablelink('updated_at', 'Last Update')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($loadouts as $loadout)
                <tr onclick="window.location = '{{ route('preview.show', $loadout->id) }}'">
                    <td><img src="/assets/img/{{ $loadout->character->image }}-hex.png"></td>
                    <td class="text-xl">{{ Str::limit($loadout->name, 50) }}</td>
                    <td class="text-xl">{{ $loadout->creator->name }}</td>
                    <td class="weapon">
                        @if($loadout->primaryGun)
                            <img src="/assets/{{ $loadout->primaryGun->image }}.svg">
                        @endif
                    </td>
                    <td class="weapon">
                        @if($loadout->secondaryGun)
                            <img src="/assets/{{ $loadout->secondaryGun->image }}.svg">
                        @endif
                    </td>
                    <td class="text-right">1</td>
                    <td class="text-sm">{{ $loadout->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $loadouts->appends(\Request::except('page'))->render() }}
    </div>

@endsection
