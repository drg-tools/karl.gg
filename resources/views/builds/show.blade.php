@extends('layouts.app')

@section('title', $build->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <x-character-icon name="{{ $build->character->name }}" /> {{ $build->name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                {{ $build->description }}
            </p>
        </div>
        <div class="grid grid-cols-2">
            @foreach($build->mods->groupBy('gun_id') as $mods)
            <div>
                <p class="text-center mt-2 mb-6 font-bold">
                    <x-gun-icon :name="$mods[0]->gun->name" size="w-40" />
                    <br>
                    {{ $mods[0]->gun->name }}
                </p>
                <ul class="md:grid md:grid-cols-2 md:col-gap-8 md:row-gap-10">
                    @foreach($mods as $mod)
                        <li>
                            <div class="flex mr-2 ml-2">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
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
