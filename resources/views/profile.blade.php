@extends('layouts.app')
@section('title', $user->name . ' - Profile')
@section('content')

<div class="profileHeaderBackground">
    <div class="profileHeaderContainer profileImageS">
        <h1>{{$user->name}}</h1>
        <div class="profileFooter">
            <div class="data-container">
                <h3>Loadouts</h3>
                <span>{{ $loadoutCount }}</span>
            </div>
            <div class="data-container">
                <h3>Salutes</h3>
                <span>{{ $salutesCount }}</span>
            </div>
            
        </div>
        
    </div>
</div>

@foreach ($loadouts as $item)
    <small-loadout-card
        :loadout-id={{$item->id}}
        :name={{$item->name}}
        :author={{$user->name}}
        :class-id={{$item->character_id}}
        :votes={{$item->getUpvotesCount($item->id)}}
        {{-- :primary="loadout.primary" --}}
        {{-- :secondary="loadout.secondary" --}}
    />
@endforeach
        
<profile-listing :loadouts-list='@json($loadouts)'></profile-listing>

@endsection
