@extends('layouts.app')

@section('title', $build->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="flex justify-between px-4 py-5 border-b border-gray-200 sm:px-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <x-character-icon name="{{ $build->character->name }}" /> {{ $build->name }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                    {{ $build->description }}
                </p>
            </div>
            @if(auth()->user()->id == $build->user_id)
            <div class="hidden sm:block rounded-md">
                <a href="{{ route('builds.edit', $build) }}">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        Edit
                    </button>
                </a>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-2">
            @foreach($build->mods->sortBy('gun_id')->groupBy('gun_id') as $mods)
            <div>
                <p class="text-center mt-2 mb-6 font-bold">
                    <x-gun-icon :name="$mods[0]->gun->name" size="40" />
                    <br>
                    {{ $mods[0]->gun->name }}
                    <br>
                    <span class="text-sm text-gray-500">({{ $mods->sortBy('row')->pluck('column')->join(', ') }})</span>
                </p>
                <ul class="md:grid md:grid-cols-2 md:col-gap-8 md:row-gap-10">
                    @foreach($mods as $mod)
                        <li>
                            <div class="flex mr-2 ml-2">
                                <div class="flex-shrink-0">
                                    <x-mod-icon name="high capacity tanks" size="10" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="text-lg leading-6 font-medium text-gray-900">{{ $mod->name }}</h5>
                                    <p class="leading-6 text-sm text-green-600">{{ $mod->effect }}</p>
                                    <p class="mt-2 text-base leading-6 text-gray-500">
                                        {{ $mod->description }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
@endsection
