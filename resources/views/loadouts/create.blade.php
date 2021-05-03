@extends('layouts.app')

@section('header')
    Build a Loadout
@endsection

@section('content')
    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
        <loadout-buttons></loadout-buttons>
        <class-select></class-select>
        <equipment-select></equipment-select>
        <div class="flex flex-wrap">
            <stats-display></stats-display>
            <modification-select></modification-select>
        </div>
    </div>

@endsection

