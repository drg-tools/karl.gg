@php
    $total = $loadouts->total();
    $summary = number_format($total).' '.Str::plural('loadout', $total)
        .($loadouts->lastPage() > 1 ? ' · page '.$loadouts->currentPage().' of '.number_format($loadouts->lastPage()) : '');
@endphp

<div class="browse-fragment" data-browse-fragment data-summary="{{ $summary }}" data-total="{{ number_format($total) }}" data-filter-count="{{ count($activeFilters) }}" data-state="{{ json_encode($browse->query() + ['sort' => $browse->sort]) }}">
    @if ($activeFilters !== [])
        <ul class="browse-chips" aria-label="Active filters">
            @foreach ($activeFilters as $filter)
                <li>
                    <a class="loadout-chip browse-chip" href="{{ $filter['url'] }}" data-browse-link aria-label="Remove filter: {{ $filter['label'] }}">
                        {{ $filter['label'] }} <span aria-hidden="true">×</span>
                    </a>
                </li>
            @endforeach
            <li>
                <a class="browse-clear" href="{{ $browse->clearUrl() }}" data-browse-link>Clear all</a>
            </li>
        </ul>
    @endif

    @if ($loadouts->isEmpty())
        @if ($browse->has('favorites'))
            <x-favorites-alert />
        @else
            <div class="loadout-notice">
                <strong>No loadouts match these filters.</strong>
                <p>Try removing a filter, or pick a different class.</p>
            </div>
        @endif
    @else
        <ul class="loadout-list browse-list">
            @foreach ($loadouts as $loadout)
                <li>
                    <x-loadout-row :loadout="$loadout" detailed :current-patch-id="$currentPatchId" :timestamp="$browse->sort === 'newest' ? 'created_at' : 'updated_at'"/>
                </li>
            @endforeach
        </ul>

        {{ $loadouts->onEachSide(1)->links('loadouts.partials.pagination') }}
    @endif
</div>
