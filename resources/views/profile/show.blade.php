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

    <div>
        <h2 class="text-gray-300">My Loadouts</h2>
        <div class="text-gray-300">
            <ul class="divide-y divide-gray-900">
                @foreach($loadouts as $loadout)
                    <li class="flex gap-4">
                        <div class="flex-grow bg-gray-700 sm:rounded-md shadow overflow-hidden">
                            <x-clickable-loadout :loadout="$loadout"/>
                        </div>
                        <div class="w-1/4 md:w-1/5 lg:w-1/6 flex flex-col justify-evenly">
                            <a class="w-full block px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded" href="/build/{{$loadout->id}}">
                                @guest
                                    Copy
                                @endguest
                                @auth
                                    @if($loadout->creator->id == auth()->user()->id)
                                        Edit
                                    @else
                                        Copy
                                    @endif
                                @endauth
                            </a>
                            @auth
                                @if($loadout->creator->id == auth()->user()->id)
                                    <form method="POST" action="/build/delete/{{$loadout->id}}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="w-full px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded" type="submit">
                                            Delete
                                        </button>
                                    </form>

                                @endif
                            @endauth

                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="my-4 py-4 sm:border-t-2 sm:border-gray-300">
                {{ $loadouts->render() }}
            </div>

        </div>
    </div>

@endsection
