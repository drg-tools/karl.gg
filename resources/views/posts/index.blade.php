@extends('layouts.app')

@section('header')
    Blog
@endsection


@section('content')
    <div class="px-4 sm:py-6 sm:px-6 lg:px-8">
        <div class="relative max-w-lg mx-auto lg:max-w-7xl">
            <div class="mt-4 grid gap-16 lg:grid-cols-3 lg:gap-x-5 lg:gap-y-12">
                @foreach($posts as $post)
                <div class="bg-gray-700 p-4 sm:p-6 sm:rounded">
                    <a href="{{ route('blog.show', $post->id) }}" class="block">
                        <p class="text-xl font-semibold text-white">
                            {{ $post->title }}
                        </p>
                        <p class="mt-3 text-base text-gray-300">
                            {!! Str::limit($post->strippedContent, 200) !!}
                        </p>
                    </a>
                    <div class="mt-6 flex items-center">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <span class="sr-only">Paul York</span>
                                <img class="h-10 w-10 rounded-full" src="{{ \Gravatar::get($post->user->email) }}" alt=""{{ $post->user->email }}>
                            </a>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-orange-500">
                                <a href="{{ route('user.profile', $post->user->id) }}">
                                    {{ $post->user->name }}
                                </a>
                            </p>
                            <div class="flex space-x-1 text-sm text-gray-300">
                                <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                    {{ $post->published_at->format('M d, Y') }}
                                </time>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="my-4 py-4 sm:border-t-2 sm:border-gray-300">
                {{ $posts->render() }}
            </div>

        </div>
    </div>
@endsection
