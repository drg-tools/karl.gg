@extends('layouts.app')

@section('header')
    {{ $loadout->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>
        <x-preview-top 
            :loadout="$loadout"
            :page-url="json_encode(Request::url())"
            :updated-at="$loadout->updated_at" 
            @if($loadout->creator)
            :creator-id="$loadout->creator->id"
            :creator-name="$loadout->creator->name"
            @else
            :creator-id="0"
            ::creator-name="Anonymous"
            @endif
        >
        </x-preview-header>
        
        <loadout-preview-page
            :loadout-data="{{ $loadout }}"
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

@endsection

