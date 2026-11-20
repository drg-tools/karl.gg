@props([
    'label',
    'name',
    'kind' => null,
    'image' => null,
    'mods' => collect(),
    'selectedIds' => [],
    'overclock' => null,
    'hasOverclockSlot' => false,
    'description' => null,
    'accent' => null,
    'code' => null,
])

@php
    $clean = fn (?string $text): string => trim(str_replace('\n', ' ', strip_tags($text ?? '')));
    $tiers = collect($mods)
        ->sortBy([['mod_tier', 'asc'], ['mod_index', 'asc']])
        ->groupBy('mod_tier')
        ->map(fn ($tierMods, $tier) => [
            'tier' => (int) $tier,
            'mods' => $tierMods,
            'chosen' => $tierMods->first(fn ($mod) => in_array($mod->id, $selectedIds, true)),
        ])
        ->values();
    $open = $tiers->whereNull('chosen');
    $openLabel = match (true) {
        $open->isEmpty() => null,
        $open->count() === 1 => 'Tier '.$open->first()['tier'].' left open',
        default => 'Tiers '.$open->first()['tier'].'–'.$open->last()['tier'].' left open',
    };
    $overclockClass = $overclock ? 'kit-oc-'.strtolower($overclock->overclock_type) : '';
@endphp

<article {{ $attributes->class('kit-card') }} @if ($accent) style="--kit-accent: {{ $accent }}" @endif>
    <div class="kit-card__head">
        <div>
            <span class="kit-card__eyebrow">{{ $label }}@if ($kind)<span class="kit-card__kind"> · {{ $kind }}</span>@endif</span>
            <h3 class="kit-card__name">{{ $name }}</h3>
            @if ($code)
                <div><span class="sr-only">Upgrade code:</span><code class="kit-card__code" title="Upgrade code: type it into the compact builder">{{ $code }}</code></div>
            @endif
        </div>
        @if ($image)
            <img class="kit-card__art" src="{{ $image }}" alt="">
        @endif
    </div>

    @if ($tiers->isNotEmpty())
        <div class="kit-card__body">
            <div class="mod-tree" aria-hidden="true">
                @foreach ($tiers as $row)
                    <div @class(['mod-tree__row', 'is-empty' => ! $row['chosen']])>
                        @foreach ($row['mods'] as $mod)
                            <span @class(['mod-tree__hex', 'is-selected' => $row['chosen']?->id === $mod->id])
                                  title="T{{ $row['tier'] }} {{ $mod->mod_index }} · {{ $mod->mod_name }}">
                                @if ($mod->icon)
                                    <img src="/assets/mods/optimized/{{ $mod->icon }}.svg" alt="">
                                @endif
                            </span>
                        @endforeach
                    </div>
                @endforeach
                @if ($hasOverclockSlot)
                    <span class="mod-tree__overclock {{ $overclockClass }}" title="{{ $overclock?->overclock_name ?? 'No overclock' }}">
                        @if ($overclock?->icon)
                            <img src="/assets/mods/optimized/{{ $overclock->icon }}.svg" alt="">
                        @else
                            —
                        @endif
                    </span>
                @endif
            </div>

            @if ($overclock)
                <p class="kit-card__oc-caption {{ $overclockClass }}">
                    <small>{{ $overclock->overclock_type }} overclock</small>
                    {{ $overclock->overclock_name }}
                </p>
            @endif

            <ol class="kit-choices">
                @foreach ($tiers->whereNotNull('chosen') as $row)
                    <li>
                        <span class="kit-choices__key">T{{ $row['tier'] }} · {{ $row['chosen']->mod_index }}</span>
                        <span>
                            <strong>{{ $row['chosen']->mod_name }}</strong>
                            <small>{{ $clean($row['chosen']->text_description ?? $row['chosen']->description) }}</small>
                        </span>
                    </li>
                @endforeach
                @if ($openLabel)
                    <li class="is-empty">
                        <span class="kit-choices__key">—</span>
                        <span><strong>{{ $openLabel }}</strong></span>
                    </li>
                @endif
                @if ($overclock)
                    <li class="{{ $overclockClass }}">
                        <span class="kit-choices__key is-overclock">OC</span>
                        <span>
                            <strong>{{ $overclock->overclock_name }}</strong>
                            <small>{{ $overclock->overclock_type }} · {{ $clean($overclock->text_description) }}</small>
                        </span>
                    </li>
                @endif
            </ol>
        </div>
    @elseif ($description)
        <p class="kit-card__description">{{ $clean($description) }}</p>
    @endif

    {{ $slot }}
</article>
