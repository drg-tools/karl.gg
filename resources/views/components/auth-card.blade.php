@props(['eyebrow', 'title', 'lede' => null])

{{--
    Narrow centred panel shared by the sign-in, registration and password
    pages. The default slot holds the form; the optional `footer` slot holds
    the "switch to another auth page" line below the panel.
--}}
<div class="loadout-page auth">
    <section {{ $attributes->class('loadout-panel auth-card') }} aria-labelledby="auth-title">
        <header class="auth-card__head">
            <p class="loadout-eyebrow">{{ $eyebrow }}</p>
            <h1 class="auth-card__title" id="auth-title">{{ $title }}</h1>
            @if ($lede)
                <p class="auth-card__lede">{{ $lede }}</p>
            @endif
        </header>

        {{ $slot }}
    </section>

    @isset($footer)
        <p class="auth-switch">{{ $footer }}</p>
    @endisset
</div>
