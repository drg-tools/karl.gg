@extends('layouts.app')

@section('header')
    Build a Loadout
@endsection

@section('content')
    <div class="text-gray-300">
        <div id="loadout-builder"><p>Loading builder…</p></div>
        <noscript>Enable JavaScript to build a loadout.</noscript>
        <div class="flex justify-center mt-8">
            <x-ads.horizontal id="3873388796" />
        </div>
    </div>

@endsection

@section('scripts')
    <script id="builder-data" type="application/json">{!! json_encode($builder, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @vite('resources/js/builder.js')
@endsection
