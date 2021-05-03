@extends('layouts.app')

@section('header')
    {{ $post->title }}
@endsection

@section('content')
    <div class="text-sm underline text-gray-300 my-2 ml-2 sm:ml-0"><a href="{{ route('blog.index') }}">Back</a></div>
    <div class="p-4 text-gray-300 bg-gray-700 sm:rounded">
        <div class="mb-4 text-gray-500">{{ $post->published_at->diffForHumans() }}</div>
        <article class="prose lg:prose-xl max-w-full">{!! $post->content !!}</article>
    </div>
@endsection
