@props(['post'])

<div {{ $attributes->class('blog-byline') }}>
    @if ($post->user)
        <img src="{{ \Gravatar::get($post->user->email) }}" alt="" loading="lazy">
    @endif
    <span class="blog-byline__copy">
        @if ($post->user)
            <a href="{{ route('user.profile', $post->user->id) }}">{{ $post->user->name }}</a>
        @else
            <span>Karl.gg</span>
        @endif
        <span class="blog-byline__meta">
            <time datetime="{{ $post->published_at->toW3CString() }}">{{ $post->published_at->format('j M Y') }}</time>
            · {{ $post->reading_minutes }} min read
        </span>
    </span>
</div>
