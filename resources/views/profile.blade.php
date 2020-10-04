@extends('layouts.app')
@section('content')

<div class="profileHeaderBackground">
    <div class="profileHeaderContainer {{$profileImgClass}}">
        <h1>{{$user->name}}</h1>

        @if (Request()->id == Auth::id())
        <div class="profileBtnContainer">
            <a href="/profile/{{Auth::id()}}/edit">
                <div class="button">
                    <h1 class="buttonText">EDIT PROFILE</h1>
                </div>
            </a>
        </div>
        @endif


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
