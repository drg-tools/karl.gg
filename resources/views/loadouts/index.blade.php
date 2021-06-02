@extends('layouts.app')

@section('styles')
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>
@endsection

@section('header')
    Browse for Loadouts
@endsection

@section('content')

    <div id="browse">

        <div class="my-2">
            <form action="{{ route('loadout.index') }}" method="GET">
                <x-filter :primaries="$primaries" :secondaries="$secondaries" :overclocks="$overclocks" />
            </form>
        </div>

        @include('loadouts.partials.table', ['loadouts' => $loadouts])

    </div>

@endsection
