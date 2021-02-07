@extends('layouts.app')

@section('body_class', 'body__home')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
@endsection

@section('content')
    <h1 class="ml-1">Find Loadouts by Class:</h2>
    <div class="flex flex-row mb-5">
        <div class="flex flex-col w-full">
            <div class="flex flex-row">
                <a href="/browse?character=3" class="flex w-1/4 px-4 py-8 dashImageD dashD mx-1 align-middle text-xl">
                        Driller
                </a>
                <a href="/browse?character=1" class="flex w-1/4 px-4 py-8 dashImageE dashE mx-1 align-middle text-xl">
                    Engineer
                </a>
                <a href="/browse?character=4" class="flex w-1/4 px-4 py-8 dashImageG dashG mx-1 align-middle text-xl">
                    Gunner
                </a>
                <a href="/browse?character=2" class="flex w-1/4 px-4 py-8 dashImageS dashS mx-1 align-middle text-xl">
                    Scout
                </a>
            </div>
        </div>
        
        
    </div>
    
    <div class="featuredLoadoutsContainer">
        <div class="flex flex-row">
            <div class="flex flex-col w-1/2">
                <x-dashboard-listing :loadoutList="$recentTopLoadouts" title="Top Loadouts in Past 2 Weeks" />
            </div>
            <div class="flex flex-col w-1/2">
                <x-dashboard-listing :loadoutList="$latestLoadouts" title='Newest Loadouts' />
            </div>
        </div>
    </div>

    <div class="featuredLoadoutsContainer px-3 pb-2">
        @foreach ($latestPosts as $post)
            <div class="my-5">
                <a href="/blog/{{$post->id}}" class="hover:underline"><h3>{{$post->title}}</h3></a>
                <div class="dashPost mt-2">
                    {!! Str::words($post->content, 50, '...') !!}
                </div>
                <a class="dashButton px-3 w-4 ml-0 mt-2" href="/blog/{{$post->id}}">
                    <span class=" text-black">READ MORE</span>
                </a>
            </div>
        @endforeach
    </div>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('/js/glider.min.js') }}"></script> --}}
    <script>
        document.addEventListener( 'DOMContentLoaded', function () {
            new Splide( '.splide' ).mount();
        } );
    </script>
@endsection
