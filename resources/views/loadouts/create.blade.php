@extends('layouts.app')

@section('header')
    Build a Loadout
@endsection

@section('content')
    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
        <div class="flex justify-center my-2">
            <x-ads.horizontal id="3873388796" />
        </div>
        <div id="loadout-builder"><p>Loading builder…</p></div>
        <noscript>Enable JavaScript to build a loadout.</noscript>
    </div>

@endsection

@section('scripts')
    <script id="builder-data" type="application/json">{!! json_encode($builder, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script src="{{ mix('/js/builder.js') }}" defer></script>
@endsection
