@props(['link' => '#', 'title', 'active' => false, 'badge' => null])

<a href="{{ $link }}" @class(['site-nav__mobile-link', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
    {{ $title }}@if ($badge) <span class="site-nav__badge">{{ $badge }}</span>@endif
</a>
