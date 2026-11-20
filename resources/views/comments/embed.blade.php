{{-- Comment thread for a page. Usage: @include('comments.embed', ['pageId' => 'loadout-1']) --}}
@php
    $commentsConfig = [
        'pageId' => $pageId,
        'loggedIn' => auth()->check(),
        'maxLength' => config('comments.max_length'),
        'locale' => app()->getLocale(),
        'translations' => trans('comments-javascript'),
    ];
@endphp

@vite('resources/js/comments/app.js')

<div class="comments-root" data-comments="{{ json_encode($commentsConfig) }}">
    <div class="comments__spinner" role="status" aria-label="Loading comments"></div>
</div>
