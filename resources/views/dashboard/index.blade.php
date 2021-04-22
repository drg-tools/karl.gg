@extends('layouts.app')

@section('body_class', 'body__home')

@section('content')
    <div class="font-sans bg-orange-100 border-t-4 border-orange-500 rounded-b text-black px-4 py-3 shadow-md mb-2" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-orange-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Patch 34 Notice</p>
                <p class="text-sm">Patch 34 is due out Thursday, April 22nd. During this time, we will be updating all of our weapon and mod data to match the new in game values. This very well could take a day or two, so please bare with us. Thanks!</p>
            </div>
        </div>
    </div>
    <h2 class="ml-1">Find Loadouts by Class:</h2>
    <div class="flex flex-row mb-5">
        <div class="flex flex-col w-full">
            <div class="flex flex-row flex-wrap lg:flex-nowrap md:flex-nowrap">
                <div class="flex flex-col w-full md:w-1/4">
                    <a href="/browse?character=3" class="px-4 py-8 dashImageD dashD mx-1 align-middle text-2xl">
                        Driller
                    </a>
                </div>
                <div class="flex flex-col w-full md:w-1/4">
                    <a href="/browse?character=1" class="px-4 py-8 dashImageE dashE mx-1 align-middle text-2xl">
                        Engineer
                    </a>
                </div>
                <div class="flex flex-col w-full md:w-1/4">
                    <a href="/browse?character=4" class="px-4 py-8 dashImageG dashG mx-1 align-middle text-2xl">
                        Gunner
                    </a>
                </div>
                <div class="flex flex-col w-full md:w-1/4">
                    <a href="/browse?character=2" class="px-4 py-8 dashImageS dashS mx-1 align-middle text-2xl">
                        Scout
                    </a>
                </div>

            </div>
        </div>


    </div>

    <div class="featuredLoadoutsContainer">
        <div class="flex flex-row flex-wrap">
            <div class="flex flex-col w-full md:w-1/2">
                <x-dashboard-listing :loadoutList="$recentTopLoadouts" title="Top Loadouts in Past 2 Weeks" />
            </div>
            <div class="flex flex-col w-full md:w-1/2">
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

