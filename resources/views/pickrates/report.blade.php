@extends('layouts.app')

@section('page-heading')
    <p>Pick rates</p>
@endsection

@php
    $tabs = [
        'classes' => ['label' => 'By class', 'route' => 'pickrates.classes'],
        'guns' => ['label' => 'By weapon', 'route' => 'pickrates.guns'],
        'mods' => ['label' => 'By mod', 'route' => 'pickrates.mods'],
    ];
    $previousLabel = $previousPatch?->label;
    $characterNames = $characters->pluck('name', 'id');
    $selectedGun = $selectedGunId ? $weapons->firstWhere('id', $selectedGunId) : null;
    $subjects = ['classes' => 'class', 'guns' => 'weapon', 'mods' => 'mod'];
@endphp

@section('content')
    <div class="loadout-page pickrates">
        <header class="browse-header pickrates-header">
            <div>
                <p class="loadout-eyebrow">
                    @if ($currentPatch)
                        {{ $currentPatch->patch_title }}
                    @else
                        Deep Rock Galactic builds
                    @endif
                </p>
                <h1 class="browse-header__title">Pick rates</h1>
                <p class="browse-header__lede">
                    How often each {{ $subjects[$type] }} shows up in builds saved this patch{{ $hasComparison ? ', and how that has moved since '.$previousLabel : '' }}.
                </p>
            </div>
            <dl class="loadout-hero__summary pickrates-summary">
                <div>
                    <dt>Builds this patch</dt>
                    <dd>{{ number_format($currentTotal) }}</dd>
                </div>
                <div>
                    <dt>Last patch{{ $hasComparison ? ' ('.$previousLabel.')' : '' }}</dt>
                    <dd>{{ $hasComparison ? number_format($previousTotal) : '—' }}</dd>
                </div>
            </dl>
        </header>

        <nav class="pickrates-tabs" aria-label="Pick rate views">
            @foreach ($tabs as $tabType => $tab)
                <a href="{{ route($tab['route']) }}" @class(['pickrates-tab', 'is-active' => $tabType === $type]) @if ($tabType === $type) aria-current="page" @endif>{{ $tab['label'] }}</a>
            @endforeach
        </nav>

        <form class="pickrates-toolbar" action="{{ route($tabs[$type]['route']) }}" method="GET" data-pickrates-form>
            @if ($type === 'guns')
                <fieldset class="pickrates-classes">
                    <legend class="browse-label">Class</legend>
                    <div class="pickrates-classes__options">
                        <label class="pickrates-class" style="--class-accent: #ffb547">
                            <input type="radio" name="character" value="" @checked($selectedCharacterId === null)>
                            <span>All</span>
                        </label>
                        @foreach ($characters as $character)
                            <label class="pickrates-class" style="--class-accent: {{ $accents[$character->name] ?? '#ffb547' }}">
                                <input type="radio" name="character" value="{{ $character->id }}" @checked($selectedCharacterId === $character->id)>
                                <span>{{ $character->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endif

            @if ($type === 'mods')
                <label class="pickrates-field">
                    <span class="browse-label">Weapon</span>
                    <select name="gun">
                        <option value="">All weapons</option>
                        @foreach ($characters as $character)
                            <optgroup label="{{ $character->name }}">
                                @foreach ($weapons->where('character_id', $character->id)->sortBy('character_slot') as $weapon)
                                    <option value="{{ $weapon->id }}" @selected($selectedGunId === $weapon->id)>{{ $weapon->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </label>
            @endif

            @if ($type !== 'classes')
                <label class="pickrates-field pickrates-field--search">
                    <span class="browse-label">Search</span>
                    <input type="search" name="search" value="{{ $search }}" placeholder="{{ $type === 'guns' ? 'Weapon name…' : 'Mod name…' }}" autocomplete="off">
                </label>
            @endif

            <label class="pickrates-field">
                <span class="browse-label">Sort</span>
                <select name="sort">
                    @foreach (\App\Http\Controllers\PickRatesController::SORTS as $value => $sortLabel)
                        <option value="{{ $value }}" @selected($sort === $value) @disabled($value !== 'picks' && ! $hasComparison) data-disabled="{{ $value !== 'picks' && ! $hasComparison ? 'true' : 'false' }}">{{ $sortLabel }}</option>
                    @endforeach
                </select>
            </label>

            <button class="loadout-button is-secondary pickrates-apply" type="submit">Apply</button>
        </form>

        <section class="loadout-section" aria-labelledby="pickrates-results">
            <h2 class="sr-only" id="pickrates-results">
                {{ $tabs[$type]['label'] }}{{ $selectedGun ? ' · '.$selectedGun->name : '' }}
            </h2>

            @if (! $currentPatch)
                <div class="loadout-notice">
                    <strong>No patch data yet.</strong>
                    <p>Pick rates appear once builds have been saved against a patch.</p>
                </div>
            @elseif ($rows->isEmpty())
                <div class="loadout-notice">
                    <strong>Nothing matches.</strong>
                    <p>Try a different search or clear the filters.</p>
                </div>
            @else
                <ol @class(['pickrates-list', 'has-comparison' => $hasComparison]) start="{{ $rankOffset + 1 }}">
                    @foreach ($rows as $row)
                        @php
                            $entity = $row['entity'];
                            $className = match ($type) {
                                'classes' => $entity->name,
                                'guns' => $characterNames[$entity->character_id] ?? '',
                                'mods' => $characterNames[$entity->gun?->character_id] ?? '',
                            };
                            $classId = match ($type) {
                                'classes' => $entity->id,
                                'guns' => $entity->character_id,
                                'mods' => $entity->gun?->character_id,
                            };
                            $browseQuery = array_filter([
                                'characters' => $classId ? [$classId] : null,
                                'primaries' => $type === 'guns' && $entity->character_slot === 1 ? [$entity->id] : null,
                                'secondaries' => $type === 'guns' && $entity->character_slot === 2 ? [$entity->id] : null,
                                'mods' => $type === 'mods' ? [$entity->id] : null,
                                'isCurrentPatch' => 1,
                            ]);
                            $deltaState = $row['delta'] > 0 ? 'is-up' : ($row['delta'] < 0 ? 'is-down' : 'is-flat');
                        @endphp
                        <li class="pickrates-row" style="--class-accent: {{ $accents[$className] ?? '#ffb547' }}">
                            <span class="pickrates-row__rank" aria-hidden="true">{{ $rankOffset + $loop->iteration }}</span>

                            <span @class(['pickrates-row__icon', 'is-portrait' => $type === 'classes'])>
                                @switch($type)
                                    @case('classes')
                                        <img src="{{ $entity->potrait }}" alt="">
                                        @break
                                    @case('guns')
                                        <img src="/assets/{{ $entity->image }}.svg" alt="">
                                        @break
                                    @default
                                        @if ($entity->icon)
                                            <img src="{{ $entity->image_svg }}" alt="">
                                        @endif
                                @endswitch
                            </span>

                            <span class="pickrates-row__copy">
                                @switch($type)
                                    @case('classes')
                                        <a class="pickrates-row__name" href="{{ route('pickrates.guns', ['character' => $entity->id]) }}">{{ $entity->name }}</a>
                                        <span class="pickrates-row__meta">See weapon pick rates →</span>
                                        @break
                                    @case('guns')
                                        <a class="pickrates-row__name" href="{{ route('pickrates.mods', ['gun' => $entity->id]) }}">{{ $entity->name }}</a>
                                        <span class="pickrates-row__meta">
                                            <span class="pickrates-row__class">{{ $className }}</span>
                                            · {{ $entity->character_slot === 1 ? 'Primary' : 'Secondary' }}
                                        </span>
                                        @break
                                    @default
                                        <span class="pickrates-row__name">{{ $entity->mod_name }}</span>
                                        <span class="pickrates-row__meta">
                                            <span class="pickrates-row__class">{{ $entity->gun?->name }}</span>
                                            · Tier {{ $entity->mod_tier }}{{ $entity->mod_index }}
                                            @if ($row['weaponShare'] !== null)
                                                · in {{ $row['weaponShare'] }}% of its weapon’s builds
                                            @endif
                                        </span>
                                @endswitch
                            </span>

                            <span class="pickrates-row__meter" aria-hidden="true">
                                <span class="pickrates-row__bar" style="width: {{ round($row['rate'] / $scaleMax * 100, 2) }}%"></span>
                                @if ($hasComparison)
                                    <span class="pickrates-row__was" style="left: {{ round($row['previousRate'] / $scaleMax * 100, 2) }}%"></span>
                                @endif
                            </span>

                            <span class="pickrates-row__rate">
                                <strong>{{ $row['rate'] }}%</strong>
                                @if ($hasComparison)
                                    <small>was {{ $row['previousRate'] }}%</small>
                                @endif
                            </span>

                            @if ($hasComparison)
                                <span @class(['pickrates-row__delta', $deltaState])>
                                    <span aria-hidden="true">{{ $row['delta'] > 0 ? '▲' : ($row['delta'] < 0 ? '▼' : '–') }}</span>
                                    {{ $row['delta'] > 0 ? '+' : '' }}{{ $row['delta'] }}<span class="sr-only"> percentage points since {{ $previousLabel }}</span><span aria-hidden="true"> pts</span>
                                </span>
                            @endif

                            <a class="pickrates-row__builds" href="{{ route('loadout.index', $browseQuery) }}">
                                {{ number_format($row['current']) }} {{ Str::plural('build', $row['current']) }}<span class="sr-only"> with {{ $type === 'mods' ? $entity->mod_name : $entity->name }} this patch</span> <span aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ol>

                @if ($paginator)
                    {{ $paginator->onEachSide(1)->links('loadouts.partials.pagination', ['label' => 'Mod pages']) }}
                @endif
            @endif
        </section>
    </div>

    <script>
        {{-- Apply filter changes straight away; the Apply button covers no-JS. --}}
        (() => {
            const form = document.querySelector('[data-pickrates-form]');
            if (!form) {
                return;
            }
            form.classList.add('is-enhanced');
            form.addEventListener('change', (event) => {
                if (event.target.name !== 'search') {
                    form.requestSubmit();
                }
            });
            form.addEventListener('submit', () => {
                [...form.elements].forEach((element) => {
                    if (element.name && (element.value === '' || (element.name === 'sort' && element.value === 'picks'))) {
                        element.disabled = true;
                    }
                });
            });
            window.addEventListener('pageshow', () => {
                [...form.elements].forEach((element) => {
                    element.disabled = element.dataset.disabled === 'true';
                });
            });
        })();
    </script>
@endsection
