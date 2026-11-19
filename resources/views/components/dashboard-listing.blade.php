@props([
    'loadoutList',
    'title',
    'eyebrow' => null,
])

@php
    $accents = [
        'Driller' => '#ebc64d',
        'Engineer' => '#eb745d',
        'Gunner' => '#9cba6f',
        'Scout' => '#68aaf6',
    ];
    $headingId = 'dashboard-'.Str::slug($title);
@endphp

<section class="loadout-section" aria-labelledby="{{ $headingId }}">
    <div class="loadout-section__heading">
        @if ($eyebrow)
            <p class="loadout-eyebrow">{{ $eyebrow }}</p>
        @endif
        <h2 id="{{ $headingId }}">{{ $title }}</h2>
    </div>
    <ul class="dashboard-list">
        @forelse ($loadoutList as $loadout)
            @php
                $guns = $loadout->mods->pluck('gun')->filter()->unique('id')->sortBy('character_slot');
            @endphp
            <li>
                <a class="dashboard-loadout" href="{{ route('preview.show', $loadout->id) }}" style="--class-accent: {{ $accents[$loadout->character->name] ?? '#ffb547' }}">
                    <span class="dashboard-loadout__copy">
                        <span class="dashboard-loadout__name">{{ $loadout->name }}</span>
                        <span class="dashboard-loadout__meta">
                            <span class="dashboard-loadout__class">{{ $loadout->character->name }}</span>
                            · {{ $loadout->creator->name ?? 'Anonymous' }}
                            · {{ $loadout->created_at->diffForHumans(short: true) }}
                        </span>
                    </span>
                    <span class="dashboard-loadout__guns">
                        @foreach ($guns as $gun)
                            <img src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }}" title="{{ $gun->name }}">
                        @endforeach
                    </span>
                    <span class="dashboard-loadout__votes" title="Salutes"><span aria-hidden="true">⬡</span> {{ round($loadout->votes_sum_value ?? 0) }}</span>
                </a>
            </li>
        @empty
            <li class="dashboard-list__empty">No loadouts in the past two weeks yet.</li>
        @endforelse
    </ul>
</section>
