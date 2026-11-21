@extends('layouts.app')

@section('content')
    <div id="loadout-calculator"><p class="text-gray-300">Loading calculator…</p></div>
    <noscript>Enable JavaScript to use the calculator.</noscript>
@endsection

@section('scripts')
    <script id="calculator-data" type="application/json">{!! json_encode($calculator, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @vite('resources/js/calculator.js')
@endsection
