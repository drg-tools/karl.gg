@props(['link' => '#', 'title', 'active' => false])

<a href="{{ $link }}" @class(['site-nav__menu-link', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
    {{ $title }}
</a>
