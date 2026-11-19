@extends('layouts.app')

@section('page-heading')
    <p>Blog</p>
@endsection

@php
    $featuredPost = $posts->onFirstPage() ? $posts->first() : null;
    $gridPosts = $featuredPost ? $posts->slice(1) : $posts;
@endphp

@section('content')
    <div class="loadout-page blog">
        <header class="browse-header">
            <div>
                <p class="loadout-eyebrow">Mission control</p>
                <h1 class="browse-header__title">Blog</h1>
                <p class="browse-header__lede">
                    Patch breakdowns, site updates and news from the Karl.gg crew.
                </p>
            </div>
            <dl class="loadout-hero__summary blog-summary">
                <div>
                    <dt>Posts</dt>
                    <dd>{{ number_format($posts->total()) }}</dd>
                </div>
                <div>
                    <dt>Latest</dt>
                    <dd>{{ $latestPublishedAt?->format('j M Y') ?? '—' }}</dd>
                </div>
            </dl>
        </header>

        @if ($posts->isEmpty())
            <div class="loadout-notice">
                <strong>No posts yet</strong>
                <p>Check back soon. The crew is still writing up the mission report.</p>
            </div>
        @endif

        @if ($featuredPost)
            <article class="blog-feature" aria-labelledby="post-{{ $featuredPost->id }}">
                <p class="loadout-eyebrow">Latest post</p>
                <h2 class="blog-feature__title" id="post-{{ $featuredPost->id }}">
                    <a href="{{ route('blog.show', $featuredPost->id) }}">{{ $featuredPost->title }}</a>
                </h2>
                <p class="blog-feature__excerpt">{!! Str::words($featuredPost->strippedContent, 60, '…') !!}</p>
                <div class="blog-feature__footer">
                    <x-blog-byline :post="$featuredPost" />
                    <a class="loadout-button" href="{{ route('blog.show', $featuredPost->id) }}" aria-label="Read the post: {{ $featuredPost->title }}">Read the post <span aria-hidden="true">→</span></a>
                </div>
            </article>
        @endif

        @if ($gridPosts->isNotEmpty())
            <section class="loadout-section" aria-labelledby="blog-archive">
                <div class="loadout-section__heading">
                    <p class="loadout-eyebrow">{{ $featuredPost ? 'Earlier dispatches' : 'Page '.$posts->currentPage() }}</p>
                    <h2 id="blog-archive">{{ $featuredPost ? 'More from the blog' : 'Older posts' }}</h2>
                </div>
                <div class="blog-grid">
                    @foreach ($gridPosts as $post)
                        <article class="blog-card">
                            <h2><a href="{{ route('blog.show', $post->id) }}">{{ $post->title }}</a></h2>
                            <p class="blog-card__excerpt">{!! Str::words($post->strippedContent, 32, '…') !!}</p>
                            <div class="blog-card__footer">
                                <x-blog-byline :post="$post" />
                                <a class="dashboard-more" href="{{ route('blog.show', $post->id) }}" aria-label="Read more: {{ $post->title }}">Read →</a>
                            </div>
                        </article>

                    @endforeach
                </div>
            </section>
        @endif

        {{ $posts->onEachSide(1)->links('loadouts.partials.pagination', ['label' => 'Blog pages']) }}
    </div>
@endsection
