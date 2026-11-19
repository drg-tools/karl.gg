@extends('layouts.app')

@section('page-heading')
    <p>Blog</p>
@endsection

@section('content')
    <div class="loadout-page blog">
        <article class="blog-post">
            <a class="blog-back" href="{{ route('blog.index') }}">← All posts</a>

            <header class="blog-post__header">
                <p class="loadout-eyebrow">Mission control · Deep Rock Galactic news</p>
                <h1 class="blog-post__title">{{ $post->title }}</h1>
                <x-blog-byline :post="$post" />
            </header>

            <div class="prose prose-invert lg:prose-lg blog-prose">{!! $post->content !!}</div>

            <footer class="blog-post__footer">
                <a class="blog-back" href="{{ route('blog.index') }}">← All posts</a>
                <a class="loadout-button" href="{{ route('builder.create') }}">Start a build <span aria-hidden="true">↗</span></a>
            </footer>
        </article>

        <div class="flex justify-center">
            <x-ads.horizontal id="1471414710" />
        </div>

        @if ($morePosts->isNotEmpty())
            <section class="loadout-section" aria-labelledby="blog-more">
                <div class="loadout-section__heading dashboard-heading">
                    <div>
                        <p class="loadout-eyebrow">Keep reading</p>
                        <h2 id="blog-more">More from the blog</h2>
                    </div>
                    <a class="dashboard-more" href="{{ route('blog.index') }}">All posts →</a>
                </div>
                <div class="blog-grid">
                    @foreach ($morePosts as $morePost)
                        <article class="blog-card">
                            <h3><a href="{{ route('blog.show', $morePost->id) }}">{{ $morePost->title }}</a></h3>
                            <p class="blog-card__excerpt">{!! Str::words($morePost->strippedContent, 24, '…') !!}</p>
                            <div class="blog-card__footer">
                                <x-blog-byline :post="$morePost" />
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
