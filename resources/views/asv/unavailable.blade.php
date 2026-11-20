@extends('layouts.app')

@section('content')
    <div class="loadout-page asv">
        <header class="browse-header">
            <div>
                <p class="loadout-eyebrow">Advanced statistics</p>
                <h1 class="browse-header__title">{{ $gun->name }}</h1>
            </div>
            <a class="loadout-button is-secondary" href="{{ url()->previous() }}"><span aria-hidden="true">←</span> Back</a>
        </header>

        <div class="loadout-notice" role="status">
            <strong>Advanced statistics are not available for this weapon and mod combination yet.</strong>
            <p>You can still view the weapon's basic statistics in the loadout builder.</p>
        </div>
    </div>
@endsection
