@extends('layouts.app')

@section('header')
    <div class="flex">
        {{ $loadout->name }}

        @auth
            <form action="{{ route('loadout.favorite', $loadout->id) }}" method="post" class="ml-4">
                @csrf
                <button type="submit" class="text-sm">
                    @if($loadout->isFavorited())
                        <i class="fas fa-heart text-pink-600"></i>
                    @else
                        <i class="far fa-heart text-gray-500"></i>

                    @endif
                </button>
            </form>
        @endauth
    </div>
@endsection

@push('styles')
    <link href="{{ mix('comments.css', 'vendor/comments') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>
        <x-preview-top :loadout="$loadout"/>

        <loadout-preview-page
            :loadout-data="{!! $loadout !!}"
            @if($loadout->primary_gun)
            :primary="{{ $loadout->primary_gun }}"
            :primary-mods="{{ $loadout->primary_gun->mods }}"
            @endif
            @if($loadout->secondary_gun)
            :secondary="{{ $loadout->secondary_gun }}"
            :secondary-mods="{{ optional($loadout->secondary_gun)->mods }}"
            @endif
            :overclocks="{{ $loadout->overclocks }}"
            :available-equipment="{{ $availableEquipment }}"
            @if($loadout->equipments)
            :equipment-mods="{{ $loadout->equipments }}"
            @endif
            @if($loadout->throwable)
            :throwable="{{ $loadout->throwable }}"
            @endif
        >
        </loadout-preview-page>
        <div class="flex justify-center my-2">
            <x-ads.horizontal id="3418109138" />
        </div>
    </div>

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md mt-4">
        <div class="p-4">
            <div id="loadout-comments"></div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ mix('comments.js', 'vendor/comments') }}"></script>
    <script>
        new Comments.default({
            el: '#loadout-comments',
            pageId: 'loadout-{{ $loadout->id }}'
        });
    </script>
@endsection
