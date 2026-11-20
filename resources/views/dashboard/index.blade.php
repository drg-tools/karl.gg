@extends('layouts.app')

@php
    $classes = [
        'Driller' => ['color' => '#ebc64d', 'role' => 'Excavate & eliminate'],
        'Engineer' => ['color' => '#eb745d', 'role' => 'Fortify & defend'],
        'Gunner' => ['color' => '#9cba6f', 'role' => 'Firepower & protection'],
        'Scout' => ['color' => '#68aaf6', 'role' => 'Light & mobility'],
    ];
    $patchLoadouts = $patchStats['patchLoadouts'];
    $topClass = $patchStats['classes']->sortByDesc('loadouts_count')->first();
@endphp

@section('content')
    <div class="loadout-page dashboard">
        @if (session('status') === 'account-deleted')
            <div class="loadout-notice" role="status">
                <strong>Your account has been deleted.</strong>
            </div>
        @endif

        <header class="dashboard-hero">
            <img class="dashboard-hero__art" src="{{ asset('assets/img/home-base.png') }}" alt="">
            <div class="dashboard-hero__copy">
                <p class="loadout-eyebrow">
                    @if ($currentPatch)
                        {{ $currentPatch->patch_title }}
                    @else
                        Deep Rock Galactic builds
                    @endif
                </p>
                <h1 class="dashboard-hero__title">Deep Rock Galactic builds, born ready</h1>

                <div class="loadout-hero__actions">
                    <a class="loadout-button" href="{{ route('builder.create') }}">Start a build</a>
                    <a class="loadout-button is-secondary" href="{{ route('loadout.index') }}">Browse loadouts</a>
                </div>

                <dl class="loadout-hero__summary dashboard-hero__stats">
                    <div>
                        <dt>Builds this patch</dt>
                        <dd>{{ number_format($patchLoadouts) }}</dd>
                    </div>
                    <div>
                        <dt>Builds all-time</dt>
                        <dd>{{ number_format($patchStats['totalLoadouts']) }}</dd>
                    </div>
                    <div>
                        <dt>Most played</dt>
                        <dd>{{ $patchLoadouts > 0 ? $topClass->name : '—' }}</dd>
                    </div>
                </dl>
            </div>
        </header>

        <section class="loadout-section" aria-labelledby="dashboard-classes">
            <div class="loadout-section__heading dashboard-heading">
                <div>
                    <h2 id="dashboard-classes">Find loadouts by class</h2>
                </div>
                <a class="dashboard-more" href="{{ route('pickrates.classes') }}">Class pick rates →</a>
            </div>
            <div class="dashboard-classes">
                @foreach ($patchStats['classes'] as $character)
                    @php
                        $meta = $classes[$character->name] ?? ['color' => '#fc9e00', 'role' => ''];
                        $share = $patchLoadouts > 0 ? round($character->loadouts_count / $patchLoadouts * 100) : 0;
                    @endphp
                    <a class="dashboard-class" href="{{ route('loadout.index') }}?characters[]={{ $character->id }}" style="--class-accent: {{ $meta['color'] }}">
                        <img class="dashboard-class__portrait" src="{{ $character->potrait }}" alt="">
                        <span class="dashboard-class__copy">
                            <span class="dashboard-class__name">{{ $character->name }}</span>
                        </span>
                        <span class="dashboard-class__share">
                            <span class="dashboard-class__meter" aria-hidden="true"><span style="width: {{ $share }}%"></span></span>
                            <span>{{ number_format($character->loadouts_count) }} {{ Str::plural("build", $character->loadouts_count) }} · {{ $share }}% this patch</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <div class="dashboard-lists">
            <x-dashboard-listing :loadoutList="$recentTopLoadouts" title="Top loadouts in past 2 weeks"/>
            <x-dashboard-listing :loadoutList="$latestLoadouts" title="Newest loadouts"/>
        </div>

        @if ($patchStats['popularOverclocks']->isNotEmpty())
            <section class="loadout-section" aria-labelledby="dashboard-overclocks">
                <div class="loadout-section__heading dashboard-heading">
                    <div>
                        <h2 id="dashboard-overclocks">Most-picked overclocks this patch</h2>
                    </div>
                    <a class="dashboard-more" href="{{ route('pickrates.guns') }}">Weapon pick rates →</a>
                </div>
                <ol class="dashboard-overclocks">
                    @foreach ($patchStats['popularOverclocks'] as $overclock)
                        <li @class(['dashboard-overclock', 'kit-oc-'.strtolower($overclock->overclock_type)])
                            style="--class-accent: {{ $classes[$overclock->character?->name]['color'] ?? '#fc9e00' }}">
                            <span class="dashboard-overclock__rank">{{ $loop->iteration }}</span>
                            <span class="dashboard-overclock__icon">
                                @if ($overclock->icon)
                                    <img src="/assets/mods/optimized/{{ $overclock->icon }}.svg" alt="">
                                @endif
                            </span>
                            <span class="dashboard-overclock__copy">
                                <strong>{{ $overclock->overclock_name }}</strong>
                                <small>{{ $overclock->gun?->name }}</small>
                            </span>
                            <span class="dashboard-overclock__meta">
                                <span class="dashboard-overclock__type">{{ $overclock->overclock_type }}</span>
                                <span>{{ number_format($overclock->loadouts_count) }} {{ Str::plural("build", $overclock->loadouts_count) }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        <x-ads.unit ad-slot="1471414710" />

        @if ($latestPosts->isNotEmpty())
            <section class="loadout-section" aria-labelledby="dashboard-posts">
                <div class="loadout-section__heading dashboard-heading">
                    <div>
                        <h2 id="dashboard-posts">From the blog</h2>
                    </div>
                    <a class="dashboard-more" href="{{ route('blog.index') }}">All posts →</a>
                </div>
                <div class="dashboard-posts">
                    @foreach ($latestPosts as $post)
                        <article class="dashboard-post">
                            <p class="loadout-eyebrow">{{ $post->published_at->format('j M Y') }}</p>
                            <h3><a href="{{ route('blog.show', $post->id) }}">{{ $post->title }}</a></h3>
                            <p class="dashboard-post__excerpt">{!! Str::words($post->strippedContent, 40, '…') !!}</p>
                            <a class="dashboard-more" href="{{ route('blog.show', $post->id) }}" aria-label="Read more: {{ $post->title }}">Read more →</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
