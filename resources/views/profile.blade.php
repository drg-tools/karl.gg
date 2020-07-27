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
<profile-listing :user-id={{$user->id}}></profile-listing>

@endsection
