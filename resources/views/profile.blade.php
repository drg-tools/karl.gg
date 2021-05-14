@extends('layouts.app')

@section('header')
    {{ $user->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
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

        <div class="w-6/12 mx-auto">
            <x-dashboard-listing :loadoutList="$loadouts" title='Newest Loadouts'/>
        </div>

@endsection
