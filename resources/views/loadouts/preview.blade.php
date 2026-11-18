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
        <x-preview-top :loadout="$loadout" :has-voted="$hasVoted" :vote-count="$voteCount"/>

        <div class="grid gap-4 p-4 md:grid-cols-2">
            @foreach ($loadout->mods->pluck('gun')->merge($loadout->overclocks->pluck('gun'))->filter()->unique('id') as $gun)
                <section class="rounded bg-gray-800 p-4">
                    <h2 class="text-lg font-bold">{{ $gun->name }}</h2>
                    <ul class="py-3">
                        @foreach ($loadout->mods->where('gun_id', $gun->id)->sortBy('mod_tier') as $mod)
                            <li title="{{ $mod->text_description }}">Tier {{ $mod->mod_tier }}: {{ $mod->mod_name }}</li>
                        @endforeach
                    </ul>
                    @foreach ($loadout->overclocks->where('gun_id', $gun->id) as $overclock)
                        <p title="{{ $overclock->text_description }}">{{ $overclock->overclock_name }}</p>
                    @endforeach
                </section>
            @endforeach
            @foreach ($loadout->equipment_mods->groupBy('equipment_id') as $mods)
                <section class="rounded bg-gray-800 p-4">
                    <h2 class="text-lg font-bold">{{ $mods->first()->equipment->name }}</h2>
                    <ul class="py-3">
                        @foreach ($mods->sortBy('mod_tier') as $mod)
                            <li title="{{ $mod->text_description }}">Tier {{ $mod->mod_tier }}: {{ $mod->mod_name }}</li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
            @if ($loadout->throwable)
                <section class="rounded bg-gray-800 p-4"><h2>{{ $loadout->throwable->name }}</h2></section>
            @endif
        </div>
        <details open class="p-4">
            <summary class="cursor-pointer text-xl text-karl-orange">Loadout guide</summary>
            <div class="prose prose-invert max-w-none py-4">{!! $guideHtml !!}</div>
        </details>
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
