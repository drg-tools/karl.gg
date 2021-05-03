@extends('layouts.app-v2')

@section('header')
    {{ $user->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
        <div class="">
            @if (Request()->id == Auth::id())
                <div class="flex justify-end">
                    <a href="/profile/{{Auth::id()}}/edit" class="hover:underline text-gray-300">Edit Profile</a>
                </div>
            @endif


            <div class="flex gap-4 mb-2 sm:mb-4">
                <div class="flex flex-col p-4 text-center">
                    <div class="text-xl text-karl-orange">{{ $loadoutCount }}</div>
                    <div class="text-sm">Loadouts</div>
                </div>
                <div class="flex flex-col p-4 text-center">
                    <div class="text-xl text-karl-orange">{{ $salutesCount }}</div>
                    <div class="text-sm">Salutes</div>
                </div>
            </div>
        </div>

        <profile-listing :user-id={{$user->id}}></profile-listing>
    </div>

@endsection
