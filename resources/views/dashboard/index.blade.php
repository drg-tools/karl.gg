@extends('layouts.app')

@section('body_class', 'body__home')

@section('content')
    <h1 class="ml-1">Find Loadouts by Class:</h2>
    <div class="flex flex-row mb-5">
        <div class="flex flex-col w-full">
            <div class="flex flex-row">
                <a href="/browse?character=3" class="flex w-1/4 px-4 py-8 dashImageD dashD mx-1 align-middle text-2xl">
                        Driller
                </a>
                <a href="/browse?character=1" class="flex w-1/4 px-4 py-8 dashImageE dashE mx-1 align-middle text-2xl">
                    Engineer
                </a>
                <a href="/browse?character=4" class="flex w-1/4 px-4 py-8 dashImageG dashG mx-1 align-middle text-2xl">
                    Gunner
                </a>
                <a href="/browse?character=2" class="flex w-1/4 px-4 py-8 dashImageS dashS mx-1 align-middle text-2xl">
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
                <a href="{{ route('blog.show', $post->id) }}" class="hover:underline"><h3>{{$post->title}}</h3></a>
                <div class="dashPost mt-2">
                    {!! Str::words($post->content, 50, '...') !!}
                </div>
                <a class="dashButton px-3 w-4 ml-0 mt-2" href="{{ route('blog.show', $post->id) }}">
                    <span class=" text-black">READ MORE</span>
                </a>
            </div>
        @endforeach
    </div>

@endsection

