@extends('layouts.app')

@section('content')

    <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>

    <preview-header :page-url="{{ json_encode(Request::url()) }}"></preview-header>
    <loadout-preview></loadout-preview>

@endsection

