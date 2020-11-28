@extends('layouts.asv')

@section('content')

    <div class="featuredLoadoutsContainer">
                    {{-- {{var_dump($buildMetrics)}} --}}

    <x-equipment-component :gun="$gun" :gunIcon="$gunIcon" :character="$character" :modMatrix="$modMatrix"></x-equipment-component>
    </div><!-- end overall equipment container -->

@endsection