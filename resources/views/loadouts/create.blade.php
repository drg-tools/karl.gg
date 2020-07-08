@extends('layouts.app')

@section('title', 'Create a Loadout')

@section('content')

    <loadout-buttons></loadout-buttons>
    <class-select></class-select>
    <equipment-select></equipment-select>
    <div class="mainContainer">
        <!--<stats-display></stats-display>-->
        <modification-select></modification-select>
    </div>

@endsection

