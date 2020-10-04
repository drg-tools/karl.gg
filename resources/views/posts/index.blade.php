@extends('layouts.app')

@section('content')
    <div id="blog">
        @foreach($posts as $post)
            <div class="mb-8 p-4">
                <h2 class="text-orange my-0"><a href="{{ route('blog.show', $post->id) }}">{{ $post->title }}</a></h2>
                <div class="mb-4 text-white">{{ $post->published_at->diffForHumans() }}</div>
                <article class="prose max-w-full text-white">{!! Str::limit($post->content, 200) !!}</article>
            </div>
        @endforeach

        {{ $posts->render() }}
    </div>
@endsection
