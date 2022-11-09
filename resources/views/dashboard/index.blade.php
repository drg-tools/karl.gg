@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
    <h2 class="text-gray-300">Find Loadouts by Class</h2>
    <div class="mb-5">
        <div class="grid grid-cols-2 md:grid-cols-4 md:gap-4">
            <a href="/browse?characters[]=3" class="block px-4 py-4 bg-driller hover:bg-driller-dark flex flex-col justify-center items-center sm:flex-row sm:justify-around shadow sm:rounded">
                <img
                    src="{{ asset('assets/img/Driller_portrait-128px.png') }}"
                    class="w-16 sm:w-24"
                    alt="Driller Logo">
                <span class="text-lg sm:text-3xl font-semibold">Driller</span>
            </a>
            <a href="/browse?characters[]=1" class="block px-4 py-4 bg-engineer hover:bg-engineer-dark flex flex-col justify-center items-center sm:flex-row sm:justify-around shadow sm:rounded">
                <img
                    src="{{ asset('assets/img/Engineer_portrait-128px.png') }}"
                    class="w-16 sm:w-24"
                    alt="Engineer Logo">
                <span class="text-lg sm:text-3xl font-semibold">Engineer</span>
            </a>
            <a href="/browse?characters[]=4" class="block px-4 py-4 bg-gunner hover:bg-gunner-dark flex flex-col justify-center items-center sm:flex-row sm:justify-around shadow sm:rounded">
                <img
                    src="{{ asset('assets/img/Gunner_portrait-128px.png') }}"
                    class="w-16 sm:w-24"
                    alt="Gunner Logo">
                <span class="text-lg sm:text-3xl font-semibold">Gunner</span>
            </a>
            <a href="/browse?characters[]=2" class="block px-4 py-4 bg-scout hover:bg-scout-dark flex flex-col justify-center items-center sm:flex-row sm:justify-around shadow sm:rounded">
                <img
                    src="{{ asset('assets/img/Scout_portrait-128px.png') }}"
                    class="w-16 sm:w-24"
                    alt="Scout Logo">
                <span class="text-lg sm:text-3xl font-semibold">Scout</span>
            </a>
        </div>
    </div>

    <div class="my-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-2">
            <x-dashboard-listing :loadoutList="$recentTopLoadouts" title="Top Loadouts in Past 2 Weeks"/>
            <x-dashboard-listing :loadoutList="$latestLoadouts" title='Newest Loadouts'/>
        </div>
    </div>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3237881937260629"
            crossorigin="anonymous"></script>
    <!-- Dashboard Ad -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-3237881937260629"
         data-ad-slot="1471414710"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        @foreach ($latestPosts as $post)
            <div class="my-5">
                <a href="{{ route('blog.show', $post->id) }}" class="hover:underline text-white text-lg"><h3>{{$post->title}}</h3></a>
                <div class="mt-2">
                    {!! Str::words($post->strippedContent, 50, '...') !!}
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

