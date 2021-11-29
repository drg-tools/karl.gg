@extends('layouts.app')

@section('header')
    {{ $loadout->name }}
@endsection

@section('styles')
    <link href="{{ mix('comments.css', 'vendor/comments') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>
        <x-preview-top :loadout="$loadout" />

        <loadout-preview-page
            :loadout-data="{!! $loadout !!}"
            @if($loadout->primary_gun)
            :primary="{{ $loadout->primary_gun }}"
            :primary-mods="{{ $loadout->primary_gun->mods }}"
            @endif
            @if($loadout->secondary_gun)
            :secondary="{{ $loadout->secondary_gun }}"
            :secondary-mods="{{ optional($loadout->secondary_gun)->mods }}"
            @endif
            :overclocks="{{ $loadout->overclocks }}"
            :available-equipment="{{ $availableEquipment }}"
            @if($loadout->equipments)
            :equipment-mods="{{ $loadout->equipments }}"
            @endif
        >
        </loadout-preview-page>
    </div>

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md mt-4">
        <div class="p-4">
            <div id="loadout-comments"></div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ mix('comments.js', 'vendor/comments') }}"></script>
    <script>
        new Comments.default({
            el: '#loadout-comments',
            pageId: 'loadout-{{ $loadout->id }}'
        });
    </script>
@endsection
