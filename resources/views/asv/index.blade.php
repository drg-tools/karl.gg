@extends('layouts.asv')

@section('content')

    <div class="featuredLoadoutsContainer">
        {{var_dump($build)}}

        {{-- HIIIIIIIIIII
        {{var_dump($character[0]->id)}} --}}
        <equipment-card
                       :classId="'{{$character[0]->id}}'"
                       :equipmentId="'{{$gun[0]->id}}'"
                       :equipmentName="'{{$gun[0]->name}}'"
                       :icon="'{{$gun[0]->image}}'"
                       {{-- :overclock="loadoutDetails.primaryWeapons[0].overclocks[0]" --}}
                       {{-- :modMatrix="loadoutDetails.primaryWeapons[0].modMatrix" --}}
                       {{-- :build="loadoutDetails.primaryWeapons[0].modString.join('')" --}}
                        >
         </equipment-card> 
    </div>

@endsection
