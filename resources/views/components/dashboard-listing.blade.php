@props([
    'loadoutList',
    'title',
    'eyebrow' => null,
])

@php
    $headingId = 'dashboard-'.Str::slug($title);
@endphp

<section class="loadout-section" aria-labelledby="{{ $headingId }}">
    <div class="loadout-section__heading">
        @if ($eyebrow)
            <p class="loadout-eyebrow">{{ $eyebrow }}</p>
        @endif
        <h2 id="{{ $headingId }}">{{ $title }}</h2>
    </div>
    <ul class="loadout-list">
        @forelse ($loadoutList as $loadout)
            <li><x-loadout-row :loadout="$loadout"/></li>
        @empty
            <li class="loadout-list__empty">No loadouts in the past two weeks yet.</li>
        @endforelse
    </ul>
</section>
