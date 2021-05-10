@extends('layouts.app')

@section('header')
    {{ $loadout->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>

        <preview-header :page-url="{{ json_encode(Request::url()) }}"></preview-header>
        <loadout-preview></loadout-preview>
    </div>

@endsection

