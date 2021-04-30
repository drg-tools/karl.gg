@extends('layouts.app-v2')

@section('header')
    Dashboard
@endsection

@section('content')
    <h2 class="text-gray-300">Find Loadouts by Class</h2>
    <div class="mb-5">
        <div class="grid grid-cols-2 md:grid-cols-4 md:gap-4">
            <a href="/browse?character=3" class="block text-2xl px-4 py-8 bg-driller hover:bg-driller-dark text-center shadow sm:rounded">
                Driller
            </a>
            <a href="/browse?character=3" class="block text-2xl px-4 py-8 bg-scout hover:bg-scout-dark text-center shadow sm:rounded">
                Scout
            </a>
            <a href="/browse?character=3" class="block text-2xl px-4 py-8 bg-gunner hover:bg-gunner-dark text-center shadow sm:rounded">
                Gunner
            </a>
            <a href="/browse?character=3" class="block text-2xl px-4 py-8 bg-engineer hover:bg-engineer-dark text-center shadow sm:rounded">
                Engineer
            </a>
        </div>
    </div>

    <div class="my-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-2">
            <x-dashboard-listing :loadoutList="$recentTopLoadouts" title="Top Loadouts in Past 2 Weeks"/>
            <x-dashboard-listing :loadoutList="$latestLoadouts" title='Newest Loadouts'/>
        </div>
    </div>

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        @foreach ($latestPosts as $post)
            <div class="my-5">
                <a href="{{ route('blog.show', $post->id) }}" class="hover:underline text-white text-lg"><h3>{{$post->title}}</h3></a>
                <div class="mt-2">
                    {{ Str::words(strip_tags($post->content), 50, '...') }}
                </div>
                <div class="text-right">
                    <a class="" href="{{ route('blog.show', $post->id) }}">
                        <span class="uppercase">Read more</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection

