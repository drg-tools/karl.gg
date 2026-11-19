@props(['link' => '#', 'title', 'active' => false])

<a href="{{ $link }}" @class(['site-nav__link', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
    {{ $title }}
</a>
