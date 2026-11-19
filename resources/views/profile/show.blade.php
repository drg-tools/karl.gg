@extends('layouts.app')

@php
    $accents = [
        'Driller' => '#ebc64d',
        'Engineer' => '#eb745d',
        'Gunner' => '#9cba6f',
        'Scout' => '#68aaf6',
    ];
    $total = $loadouts->total();
    $summary = number_format($total).' '.Str::plural('loadout', $total)
        .($loadouts->lastPage() > 1 ? ' · page '.$loadouts->currentPage().' of '.number_format($loadouts->lastPage()) : '');
    $tabUrl = fn (?int $classId) => route('user.profile', array_filter([
        'id' => $user->id,
        'class' => $classId,
        'sort' => $sort === \App\LoadoutBrowseQuery::DEFAULT_SORT ? null : $sort,
    ])).'#loadouts';
@endphp

@section('content')
    <div class="loadout-page profile">
        @if (session('status'))
            <div class="loadout-notice" role="status">
                <strong>{{ session('status') }}</strong>
            </div>
        @endif

        <header class="profile-hero">
            <div class="profile-hero__identity">
                <img class="profile-hero__avatar" src="{{ \Gravatar::get($user->email, ['size' => 160]) }}" alt="" width="80" height="80">
                <div>
                    <p class="loadout-eyebrow">{{ $isOwner ? 'Your profile' : 'Miner profile' }}</p>
                    <h1 class="profile-hero__name">{{ $user->name }}</h1>
                    @if ($user->created_at)
                        <p class="profile-hero__meta">Mining with Karl.gg since <time datetime="{{ $user->created_at->toDateString() }}">{{ $user->created_at->format('F Y') }}</time></p>
                    @endif
                </div>
            </div>

            @if ($isOwner)
                <div class="profile-hero__actions">
                    <a class="loadout-button" href="{{ route('builder.create') }}">Start a build <span aria-hidden="true">↗︎</span></a>
                    <a class="loadout-button is-secondary" href="{{ route('account.settings') }}">Account settings</a>
                </div>
            @endif

            <dl class="loadout-hero__summary profile-stats">
                <div>
                    <dt>Loadouts</dt>
                    <dd>{{ number_format($stats['loadouts']) }}</dd>
                </div>
                <div>
                    <dt>Salutes</dt>
                    <dd><span class="profile-stats__mark" aria-hidden="true">⬡</span> {{ number_format($stats['salutes']) }}</dd>
                </div>
                <div>
                    <dt>Current patch</dt>
                    <dd>{{ number_format($stats['currentPatch']) }}</dd>
                </div>
                <div>
                    <dt>Member since</dt>
                    <dd>{{ $user->created_at?->format('M Y') ?? '—' }}</dd>
                </div>
            </dl>
        </header>

        @if ($topLoadouts->isNotEmpty())
            <section class="loadout-section" aria-labelledby="profile-top-title">
                <div class="loadout-section__heading">
                    <p class="loadout-eyebrow">Most saluted</p>
                    <h2 id="profile-top-title">Top builds</h2>
                </div>
                <ol class="profile-top">
                    @foreach ($topLoadouts as $loadout)
                        @php
                            $guns = $loadout->mods->pluck('gun')->merge($loadout->overclocks->pluck('gun'))->filter()->unique('id')->sortBy('character_slot');
                            $overclock = $loadout->overclocks->first();
                        @endphp
                        <li>
                            <a class="profile-top__card" href="{{ route('preview.show', $loadout->id) }}" style="--class-accent: {{ $accents[$loadout->character->name] ?? '#ffb547' }}">
                                <span class="profile-top__head">
                                    <span class="profile-top__class">{{ $loadout->character->name }}</span>
                                    <span class="profile-top__rank" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                                </span>
                                <span class="profile-top__name">{{ $loadout->name }}</span>
                                <span class="profile-top__guns">
                                    @foreach ($guns as $gun)
                                        <img src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }}" title="{{ $gun->name }}">
                                    @endforeach
                                </span>
                                <span class="profile-top__foot">
                                    @if ($overclock)
                                        <span @class(['loadout-row__oc', 'kit-oc-'.strtolower($overclock->overclock_type)])>
                                            <span class="loadout-row__oc-mark" aria-hidden="true"></span>
                                            <span class="sr-only">Overclock:</span> {{ $overclock->overclock_name }}
                                        </span>
                                    @else
                                        <span class="loadout-row__oc is-empty">No overclock</span>
                                    @endif
                                    <span class="loadout-row__votes" title="Salutes"><span aria-hidden="true">⬡</span><span class="sr-only">Salutes:</span> {{ round($loadout->votes_sum_value) }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        <section class="loadout-section" id="loadouts" aria-labelledby="profile-loadouts-title">
            <div class="loadout-section__heading">
                <p class="loadout-eyebrow">Loadout library</p>
                <h2 id="profile-loadouts-title">{{ $isOwner ? 'Your builds' : 'Builds by '.$user->name }}</h2>
            </div>

            @if ($stats['loadouts'] === 0)
                <div class="loadout-notice">
                    @if ($isOwner)
                        <strong>You haven't shared a build yet.</strong>
                        <p>Builds you save will show up here. <a class="browse-clear" href="{{ route('builder.create') }}">Start a build →</a></p>
                    @else
                        <strong>No builds yet.</strong>
                        <p>{{ $user->name }} hasn't shared any loadouts.</p>
                    @endif
                </div>
            @else
                <div class="profile-toolbar">
                    @if ($classTabs !== [])
                        <nav class="profile-classes" aria-label="Filter by class">
                            <a class="profile-class" href="{{ $tabUrl(null) }}" style="--class-accent: #ffb547" @if ($classId === null) aria-current="page" @endif>
                                All <span>{{ number_format($stats['loadouts']) }}</span>
                            </a>
                            @foreach ($classTabs as $tab)
                                <a class="profile-class" href="{{ $tabUrl($tab['id']) }}" style="--class-accent: {{ $tab['accent'] }}" @if ($classId === $tab['id']) aria-current="page" @endif>
                                    {{ $tab['name'] }} <span>{{ number_format($tab['count']) }}</span>
                                </a>
                            @endforeach
                        </nav>
                    @endif

                    <p class="browse-count">{{ $summary }}</p>

                    <form class="browse-sort" action="{{ route('user.profile', $user->id) }}#loadouts" method="GET" x-data>
                        @if ($classId)
                            <input type="hidden" name="class" value="{{ $classId }}">
                        @endif
                        <label class="browse-label" for="profile-sort">Sort</label>
                        <select id="profile-sort" name="sort" x-on:change="$el.form.submit()">
                            @foreach (\App\LoadoutBrowseQuery::SORTS as $value => $sortLabel)
                                <option value="{{ $value }}" @selected($sort === $value)>{{ $sortLabel }}</option>
                            @endforeach
                        </select>
                        <noscript>
                            <button class="loadout-button is-secondary" type="submit">Sort</button>
                        </noscript>
                    </form>
                </div>

                <ul class="loadout-list profile-list">
                    @foreach ($loadouts as $loadout)
                        <li @class(['profile-list__item', 'has-actions' => $isOwner])>
                            <x-loadout-row :loadout="$loadout" detailed :current-patch-id="$currentPatchId" :timestamp="$sort === 'newest' ? 'created_at' : 'updated_at'"/>
                            @if ($isOwner)
                                <div class="profile-actions">
                                    <a class="profile-action" href="{{ route('build.show', $loadout->id) }}" aria-label="Edit {{ $loadout->name }}">Edit</a>
                                    <form method="POST" action="/build/delete/{{ $loadout->id }}" x-data x-on:submit="if (! confirm('Delete ' + @js($loadout->name) + '? This can\'t be undone.')) $event.preventDefault()">
                                        @method('DELETE')
                                        @csrf
                                        <button class="profile-action is-danger" type="submit" aria-label="Delete {{ $loadout->name }}">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

                {{ $loadouts->onEachSide(1)->fragment('loadouts')->links('loadouts.partials.pagination', ['label' => 'Profile loadout pages']) }}
            @endif
        </section>
    </div>
@endsection
