@props([
    'title',
    'active' => false,
])

<button type="button" {{ $attributes->class(['site-nav__link', 'is-active' => $active]) }} aria-haspopup="true">
    {{ $title }}
    <svg class="site-nav__chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
    </svg>
</button>
