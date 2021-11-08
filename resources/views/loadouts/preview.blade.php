@extends('layouts.app')

@section('header')
    {{ $loadout->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>
        {{-- {{$loadout}} --}}
        {{-- {{$loadout->mods}} --}}
        {{-- {{$loadout->primary_gun->mods}} --}}
        {{-- {{$loadout->secondary_gun}} --}}
        {{-- {{$loadout->equipments}}   --}}

        {{-- {{$loadout->overclocks}} --}}
        {{-- <preview-header :page-url="{{ json_encode(Request::url()) }}"></preview-header> --}}
        <loadout-preview-page 
            :loadout-data="{{$loadout}}"
            :primary="{{$loadout->primary_gun}}"
            :primary-mods="{{$loadout->primary_gun->mods}}"
            :secondary="{{$loadout->secondary_gun}}" 
            :secondary-mods="{{$loadout->secondary_gun->mods}}"
            :overclocks="{{$loadout->overclocks}}"
            :equipments="{{$loadout->equipments}}">
        </loadout-preview-page>
    </div>

@endsection

