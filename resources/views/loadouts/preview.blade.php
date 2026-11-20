@extends('layouts.app')

@php
    $classes = [
        'Engineer' => ['letter' => 'E', 'color' => '#eb745d', 'role' => 'Fortify & defend'],
        'Scout' => ['letter' => 'S', 'color' => '#68aaf6', 'role' => 'Light & mobility'],
        'Driller' => ['letter' => 'D', 'color' => '#ebc64d', 'role' => 'Excavate & eliminate'],
        'Gunner' => ['letter' => 'G', 'color' => '#9cba6f', 'role' => 'Firepower & protection'],
    ];
    $class = $classes[$loadout->character->name] ?? ['letter' => 'E', 'color' => '#fc9e00', 'role' => ''];
    $selectedMods = $loadout->mods->modelKeys();
    $selectedEquipmentMods = $loadout->equipment_mods->modelKeys();
    $guns = $loadout->mods->pluck('gun')
        ->merge($loadout->overclocks->pluck('gun'))
        ->filter()
        ->unique('id')
        ->sortBy('character_slot');
    $equipments = $loadout->equipment_mods->pluck('equipment')->filter()->unique('id');
    $kitViewKey = 'karl:loadout-view';
    /** Search crawlers always start detailed, so the upgrade descriptions are visible when the page is indexed. */
    $compactOnPhones = ! preg_match('/bot|crawl|spider|slurp|mediapartners/i', request()->userAgent() ?? '');
@endphp

@section('content')
    {{-- Apply the saved kit layout before the cards paint, so compact readers never see the detailed cards flash.
         Without a saved choice, phone-sized screens (the page's 760px breakpoint) start compact, except for crawlers. --}}
    <script>(function () { var view = @js($compactOnPhones) && window.matchMedia('(max-width: 760px)').matches ? 'compact' : 'detailed'; try { view = localStorage.getItem(@js($kitViewKey)) || view; } catch (e) {} document.documentElement.classList.toggle('is-kit-compact', view === 'compact'); })();</script>

    <div class="loadout-page" style="--class-accent: {{ $class['color'] }}">
        <header class="loadout-hero">
            <img class="loadout-hero__art" src="/assets/img/full-{{ $class['letter'] }}_image.png" alt="">
            <div class="loadout-hero__copy">
                <p class="loadout-eyebrow">
                    <span class="loadout-hero__class">{{ $loadout->character->name }}</span> loadout
                    @if ($isCurrentPatch)
                        · Current Patch
                    @elseif ($loadout->patch)
                        · {{ $loadout->patch->label }}
                    @endif
                    @unless ($isCurrentPatch)
                        <span class="loadout-hero__outdated" title="Made in an earlier patch, so some upgrades may perform differently today.">
                            <span aria-hidden="true">&#x26A0;&#xFE0E;</span> Older patch<span class="sr-only">: made in an earlier patch, so some upgrades may perform differently today.</span>
                        </span>
                    @endunless
                </p>
                <h1 class="loadout-hero__name">{{ $loadout->name }}</h1>
                <p class="loadout-hero__meta">
                    by
                    @if ($loadout->creator)
                        <a href="/profile/{{ $loadout->creator->id }}">{{ $loadout->creator->name }}</a>
                    @else
                        <span>Anonymous</span>
                    @endif
                    · Updated {{ $loadout->updated_at->format('j M Y') }}
                </p>

                <div class="loadout-hero__actions">
                    <div class="loadout-hero__buttons">
                        <a class="loadout-button" href="{{ route('build.show', $loadout->id) }}">Use this build</a>
                        {{-- The share sheet where the device has one (phones), otherwise copy the link. --}}
                        <button type="button" class="loadout-button is-secondary loadout-share"
                                data-share-url="{{ route('preview.show', $loadout->id) }}"
                                x-data="{
                                    copied: false,
                                    async share() {
                                        const url = this.$el.dataset.shareUrl;
                                        if (navigator.share) {
                                            try { await navigator.share({ title: @js($loadout->name), url }); return; }
                                            catch (e) { if (e.name === 'AbortError') { return; } }
                                        }
                                        try {
                                            await navigator.clipboard.writeText(url);
                                            this.copied = true;
                                            setTimeout(() => this.copied = false, 2000);
                                        } catch (e) {
                                            window.prompt('Copy this link', url);
                                        }
                                    },
                                }"
                                x-on:click="share()">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7M16 6l-4-4-4 4M12 2v13"/></svg>
                            <span x-text="copied ? 'Link copied' : 'Share'">Share</span>
                        </button>
                        @can('update', $loadout)
                            <a class="loadout-button is-secondary" href="/build/{{ $loadout->id }}">Edit</a>
                        @endcan
                    </div>
                    <div class="loadout-hero__chips">
                        @auth
                            <form action="{{ route('loadout.vote', $loadout) }}" method="post">
                                @csrf
                                <button class="loadout-chip" type="submit" aria-pressed="{{ $hasVoted ? 'true' : 'false' }}">
                                    <span aria-hidden="true">⬡</span> {{ $hasVoted ? 'Saluted' : 'Salute' }} · {{ $voteCount }}
                                </button>
                            </form>
                            <form action="{{ route('loadout.favorite', $loadout->id) }}" method="post">
                                @csrf
                                <button class="loadout-chip is-favorite" type="submit" aria-pressed="{{ $loadout->isFavorited() ? 'true' : 'false' }}">
                                    <svg class="loadout-heart" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 20.5s-7.5-4.6-9.4-9.3C1.3 7.9 3.4 4.5 6.9 4.5c2.1 0 3.9 1.2 5.1 3 1.2-1.8 3-3 5.1-3 3.5 0 5.6 3.4 4.3 6.7-1.9 4.7-9.4 9.3-9.4 9.3z"/>
                                    </svg>
                                    {{ $loadout->isFavorited() ? 'Favorited' : 'Favorite' }}
                                </button>
                            </form>
                        @else
                            <span class="loadout-chip is-static"><span aria-hidden="true">⬡</span> Salutes · {{ $voteCount }}</span>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <section class="loadout-section" aria-labelledby="loadout-kit">
            <div class="loadout-section__heading has-toggle"
                 x-data="{
                     compact: document.documentElement.classList.contains('is-kit-compact'),
                     show(compact) {
                         this.compact = compact;
                         document.documentElement.classList.toggle('is-kit-compact', compact);
                         try { localStorage.setItem(@js($kitViewKey), compact ? 'compact' : 'detailed'); } catch (e) {}
                     },
                 }">
                <h2 id="loadout-kit">Weapons &amp; gear</h2>
                <div class="loadout-view-toggle" role="group" aria-label="Weapons and gear layout">
                    <button type="button" title="Every upgrade with its full description"
                            :class="{ 'is-active': ! compact }" :aria-pressed="! compact" x-on:click="show(false)">Detailed</button>
                    <button type="button" title="Every tree on one screen, icons only"
                            :class="{ 'is-active': compact }" :aria-pressed="compact" x-on:click="show(true)">Compact</button>
                </div>
            </div>
            <div class="loadout-kit">
                @foreach ($guns as $gun)
                    <x-kit-card
                        :label="$gun->character_slot === 1 ? 'Primary' : 'Secondary'"
                        :name="$gun->name"
                        :kind="$gun->gun_class"
                        :image="'/assets/'.$gun->image.'.svg'"
                        :mods="$gun->mods"
                        :selected-ids="$selectedMods"
                        :overclock="$loadout->overclocks->firstWhere('gun_id', $gun->id)"
                        :has-overclock-slot="true"
                        :accent="$class['color']"
                        :code="$loadout->upgradeCode($gun->id)"
                    />
                @endforeach
                @foreach ($equipments as $equipment)
                    <x-kit-card
                        label="Equipment"
                        :name="$equipment->name"
                        :image="'/assets/'.$equipment->icon.'.svg'"
                        :mods="$equipment->equipment_mods"
                        :selected-ids="$selectedEquipmentMods"
                        :accent="$class['color']"
                    />
                @endforeach
                @if ($loadout->throwable)
                    <x-kit-card
                        label="Throwable"
                        :name="$loadout->throwable->name"
                        :image="'/assets/'.$loadout->throwable->icon.'.svg'"
                        :description="$loadout->throwable->description"
                        :accent="$class['color']"
                    />
                @endif
            </div>
        </section>

        <x-ads.unit ad-slot="6984291185" />

        @if (trim($guideHtml) !== '')
            <section class="loadout-section" aria-labelledby="loadout-guide">
                <div class="loadout-section__heading">
                    <h2 id="loadout-guide">Loadout guide</h2>
                </div>
                <div class="loadout-panel prose prose-invert max-w-none">{!! $guideHtml !!}</div>
            </section>

            {{-- Only with a guide between them, so the two units never stack. --}}
            <x-ads.unit ad-slot="3418109138" />
        @endif

        <section class="loadout-section" aria-labelledby="loadout-comments-heading">
            <div class="loadout-section__heading">
                <h2 id="loadout-comments-heading">Comments</h2>
            </div>
            <div class="loadout-panel">
                @include('comments.embed', ['pageId' => 'loadout-'.$loadout->id])
            </div>
        </section>
    </div>
@endsection
