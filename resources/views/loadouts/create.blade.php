@extends('layouts.app')

@section('content')
    <loadout-buttons-poc></loadout-buttons-poc>
    <class-select></class-select>
    <equipment-select></equipment-select>
    <div class="mainContainer">
        <stats-display></stats-display>
        <modification-select></modification-select>
    </div>

@endsection

