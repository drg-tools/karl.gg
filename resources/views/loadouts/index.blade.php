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

                      {{-- {{dd(Request::all())}} --}}
                {{-- @foreach(\Request::all() as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ is_array($val) ? json_encode($val) : $val }}">
                @endforeach  --}}
                
                <x-filter :primaries="$primaries" :secondaries="$secondaries" />
            </form>
        </div>

        @include('loadouts.partials.table', ['loadouts' => $loadouts])

    </div>

@endsection
