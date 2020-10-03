@extends('layouts.app')

@section('content')
    @foreach($posts as $post)
        <div class="mb-8">
            <h2 class="text-orange mb-0"><a href="{{ route('blog.show', $post->id) }}">{{ $post->title }}</a></h2>
            <div class="mb-4 text-white">{{ $post->published_at->diffForHumans() }}</div>
            <article class="text-white">{!! Str::limit($post->content, 400) !!}</article>
        </div>
    @endforeach

    {{ $posts->render() }}
@endsection
