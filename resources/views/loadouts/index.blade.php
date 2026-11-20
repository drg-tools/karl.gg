@extends('layouts.app')

@php
    $classIds = $browse->classIds();
    $selectedClassId = count($classIds) === 1 ? $classIds[0] : null;
    $legacyClassIds = count($classIds) > 1 ? $classIds : [];
    $total = $loadouts->total();
    $summary = number_format($total).' '.Str::plural('loadout', $total)
        .($loadouts->lastPage() > 1 ? ' · page '.$loadouts->currentPage().' of '.number_format($loadouts->lastPage()) : '');
    $toggleGroups = [
        'hasOverclock' => 'Overclock',
        'hasGuide' => 'Guide',
    ];
@endphp

@section('content')
    <div class="loadout-page browse" data-browse>
        <header class="browse-header">
            <div>
                <h1 class="browse-header__title">Browse loadouts</h1>
                <p class="browse-header__lede">Narrow it down by class, weapon, overclock or mod to find a build worth borrowing.</p>
            </div>
            <a class="loadout-button" href="{{ route('builder.create') }}">Start a build</a>
        </header>

        <div class="browse-layout">
            <div class="browse-backdrop" data-browse-close hidden></div>

            <div class="browse-sidebar">
                <aside class="browse-filters" id="browse-filters" aria-labelledby="browse-filters-title" data-browse-drawer>
                    <form class="browse-form" id="browse-form" action="{{ route('loadout.index') }}" method="GET" data-browse-form>
                        <div class="browse-filters__head">
                            <h2 id="browse-filters-title">Filters</h2>
                            <a class="browse-clear" href="{{ $browse->clearUrl() }}" data-browse-link @if (! $browse->isFiltered()) hidden @endif data-browse-clear>Clear all</a>
                            <button class="browse-filters__close" type="button" data-browse-close aria-label="Close filters">×</button>
                        </div>

                        <label class="browse-search">
                            <span class="browse-label">Loadout name</span>
                            <input type="search" name="search" value="{{ $browse->filters['search'] ?? '' }}" placeholder="Search by name…" autocomplete="off" data-browse-search>
                        </label>

                        <fieldset class="browse-classes">
                            <legend class="browse-label">Class</legend>
                            @foreach ($legacyClassIds as $legacyClassId)
                                <input type="hidden" name="characters[]" value="{{ $legacyClassId }}" data-legacy-class>
                            @endforeach
                            <div class="browse-classes__options">
                                <label class="browse-class is-all" style="--class-accent: #fc9e00">
                                    <input type="radio" name="characters[]" value="" @checked($selectedClassId === null && $legacyClassIds === [])>
                                    <span class="browse-class__name">All classes</span>
                                </label>
                                @foreach ($catalog['characters'] as $character)
                                    <label class="browse-class" style="--class-accent: {{ $accents[$character->name] ?? '#fc9e00' }}">
                                        <input type="radio" name="characters[]" value="{{ $character->id }}" @checked($selectedClassId === $character->id)>
                                        <img class="browse-class__mark" src="{{ $character->potrait }}" alt="">
                                        <span class="browse-class__name">{{ $character->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if ($legacyClassIds !== [])
                                <p class="browse-picker__note" data-legacy-class-note>
                                    Showing {{ $catalog['characters']->whereIn('id', $legacyClassIds)->pluck('name')->join(', ', ' and ') }}. Pick one class to narrow it down.
                                </p>
                            @endif
                        </fieldset>

                        <div class="browse-pickers">
                            <x-browse-picker name="primaries" label="Primary weapon" :groups="$pickers['primaries']" :selected="$browse->ids('primaries')" :class-ids="$classIds"/>
                            <x-browse-picker name="secondaries" label="Secondary weapon" :groups="$pickers['secondaries']" :selected="$browse->ids('secondaries')" :class-ids="$classIds"/>
                            <x-browse-picker name="overclocks" label="Overclock" :groups="$pickers['overclocks']" :selected="$browse->ids('overclocks')" :class-ids="$classIds"/>
                            <x-browse-picker name="mods" label="Mods" :groups="$pickers['mods']" :selected="$browse->ids('mods')" :class-ids="$classIds" requires-class prompt="Pick a class above to choose mods, grouped by weapon."/>
                            <x-browse-picker name="throwables" label="Throwable" :groups="$pickers['throwables']" :selected="$browse->ids('throwables')" :class-ids="$classIds"/>
                        </div>

                        @foreach ($toggleGroups as $key => $label)
                            <fieldset class="browse-segment">
                                <legend class="browse-label">{{ $label }}</legend>
                                <div class="browse-segment__options">
                                    @foreach (['' => 'Any', 'Yes' => 'With', 'No' => 'Without'] as $value => $optionLabel)
                                        <label>
                                            <input type="radio" name="{{ $key }}" value="{{ $value }}" @checked(($browse->filters[$key] ?? '') === $value)>
                                            <span>{{ $optionLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach

                        <div class="browse-checks">
                            <label class="browse-check">
                                <input type="checkbox" name="isCurrentPatch" value="1" @checked($browse->has('isCurrentPatch'))>
                                <span>Current patch only</span>
                            </label>
                            @if (auth()->check() || $browse->has('favorites'))
                                <label class="browse-check">
                                    <input type="checkbox" name="favorites" value="1" @checked($browse->has('favorites'))>
                                    <span>My favorites</span>
                                </label>
                            @endif
                        </div>

                        <div class="browse-filters__foot">
                            <button class="loadout-button browse-apply" type="submit">Apply filters</button>
                            <button class="loadout-button browse-show" type="button" data-browse-close>Show <span data-browse-count-short>{{ number_format($total) }}</span> results</button>
                        </div>
                    </form>
                </aside>

                <x-ads.unit ad-slot="6546353660" format="rectangle" class="browse-sidebar__ad" />
            </div>

            <section class="browse-results" aria-labelledby="browse-results-title" data-browse-results>
                <h2 class="sr-only" id="browse-results-title">Results</h2>
                <div class="browse-toolbar">
                    <button class="loadout-button is-secondary browse-toggle" type="button" aria-controls="browse-filters" aria-expanded="false" data-browse-open>
                        Filters
                        <span class="browse-toggle__count" data-browse-filter-count @if (count($activeFilters) === 0) hidden @endif>{{ count($activeFilters) }}</span>
                    </button>
                    <p class="browse-count" aria-live="polite" aria-atomic="true" data-browse-count>{{ $summary }}</p>
                    <label class="browse-sort">
                        <span class="browse-label">Sort</span>
                        <select name="sort" form="browse-form" data-browse-sort>
                            @foreach (\App\LoadoutBrowseQuery::SORTS as $value => $sortLabel)
                                <option value="{{ $value }}" @selected($browse->sort === $value)>{{ $sortLabel }}</option>
                            @endforeach
                        </select>
                    </label>
                    <noscript>
                        <button class="loadout-button is-secondary" type="submit" form="browse-form">Sort</button>
                    </noscript>
                </div>

                <div class="browse-results__body" data-browse-body aria-busy="false">
                    @include('loadouts.partials.results')
                </div>

                <x-ads.unit ad-slot="3816695658" />
            </section>
        </div>
    </div>
@endsection
