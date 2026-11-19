@props([
    'loadout',
    'detailed' => false,
    'currentPatchId' => null,
    'timestamp' => 'created_at',
])

@php
    $accents = [
        'Driller' => '#ebc64d',
        'Engineer' => '#eb745d',
        'Gunner' => '#9cba6f',
        'Scout' => '#68aaf6',
    ];
    $overclocks = $detailed ? $loadout->overclocks : collect();
    $guns = $loadout->mods->pluck('gun')
        ->merge($overclocks->pluck('gun'))
        ->filter()
        ->unique('id')
        ->sortBy('character_slot');
    $isCurrentPatch = $currentPatchId !== null && $loadout->patch_id === $currentPatchId;
@endphp

<a @class(['loadout-row', 'is-detailed' => $detailed]) href="{{ route('preview.show', $loadout->id) }}" style="--class-accent: {{ $accents[$loadout->character->name] ?? '#ffb547' }}">
    <span class="loadout-row__copy">
        <span class="loadout-row__name">{{ $loadout->name }}</span>
        <span class="loadout-row__meta">
            <span class="loadout-row__class">{{ $loadout->character->name }}</span>
            · {{ $loadout->creator->name ?? 'Anonymous' }}
            @if ($detailed && $loadout->patch)
                · <span @class(['loadout-row__patch', 'is-current' => $isCurrentPatch])>Patch {{ $loadout->patch->patch_num_dev ?? $loadout->patch->patch_num }}</span>
            @endif
            · {{ $detailed && $timestamp === 'updated_at' ? 'updated ' : '' }}{{ $loadout->{$timestamp}->diffForHumans(short: true) }}
        </span>
    </span>
    <span class="loadout-row__guns">
        @foreach ($guns as $gun)
            <img src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }}" title="{{ $gun->name }}">
        @endforeach
    </span>
    @if ($detailed)
        <span class="loadout-row__overclock">
            @forelse ($overclocks as $overclock)
                <span @class(['loadout-row__oc', 'kit-oc-'.strtolower($overclock->overclock_type)]) title="{{ $overclock->overclock_name }} ({{ $overclock->overclock_type }} overclock)">
                    <span class="loadout-row__oc-mark" aria-hidden="true"></span>
                    <span class="sr-only">Overclock:</span> {{ $overclock->overclock_name }}
                </span>
            @empty
                <span class="loadout-row__oc is-empty">No overclock</span>
            @endforelse
        </span>
    @endif
    <span class="loadout-row__votes" title="Salutes"><span aria-hidden="true">⬡</span><span class="sr-only">Salutes:</span> {{ round($loadout->votes_sum_value ?? 0) }}</span>
</a>
