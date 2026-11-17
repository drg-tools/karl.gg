@extends('layouts.app')

@section('header')
    {{ $gun->name }}
@endsection

@section('content')
    <p>Advanced statistics are not available for this weapon and mod combination yet.</p>
    <p>You can still view the weapon's basic statistics in the loadout builder.</p>
@endsection
