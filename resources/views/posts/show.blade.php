@extends('layouts.app')

@section('content')
    <div>
        <div class="text-sm underline"><a href="{{ route('blog.index') }}">Back</a></div>
        <h2 class="text-orange mb-0">{{ $post->title }}</h2>
        <div class="mb-4 text-white">{{ $post->published_at->diffForHumans() }}</div>
        <article class="prose prose-xl max-w-full text-white">{!! $post->content !!}</article>
    </div>
@endsection
