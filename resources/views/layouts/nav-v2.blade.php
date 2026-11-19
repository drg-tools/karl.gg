@php
    $isBuilding = \Request::is('build') || \Request::is('build/*');
@endphp

<nav class="site-nav" x-data="{ mobileOpen: false, profileOpen: false, openBuild: false }" aria-label="Main">
    <div class="site-nav__bar">
        <a class="site-nav__brand" href="/">
            <img src="{{ asset('assets/img/k-logo.svg') }}" alt="">
            <span>{{ config('app.name') }}</span>
        </a>

        <div class="site-nav__links">
            <x-nav-link :active="\Request::is('/')" link="/" title="Dashboard"/>

            <div class="site-nav__dropdown" x-on:click.away="openBuild = false">
                <x-nav-dropdown title="Loadouts" x-on:click="openBuild = !openBuild" ::aria-expanded="openBuild.toString()" aria-controls="loadouts-menu"
                    :active="\Request::is('browse') || \Request::is('pickrates/*') || \Request::is('preview/*')"/>

                <div x-show="openBuild" style="display:none;" class="site-nav__menu" id="loadouts-menu">
                    <x-nav-dropdown-link :link="route('loadout.index')" :active="\Request::is('browse') && !\Request::query('favorites')" title="Browse" />
                    @auth
                        <x-nav-dropdown-link :link="route('loadout.index', ['favorites' => 1])" :active="\Request::query('favorites')" title="Favorites" />
                    @endauth
                    <x-nav-dropdown-link :link="route('pickrates.classes')" :active="\Request::is('pickrates/*')" title="Pick Rates" />
                </div>
            </div>

            <x-nav-link :active="\Request::is('blog') || \Request::is('blog/*')" link="/blog" title="Blog"/>

            @can('view-admin')
                <x-nav-link link="/admin" title="Admin"/>
            @endcan
        </div>

        <div class="site-nav__actions">
            <a href="/build" @class(['site-nav__cta', 'is-active' => $isBuilding]) @if ($isBuilding) aria-current="page" @endif>
                Build <span aria-hidden="true">↗</span>
            </a>

            @guest
                <a class="site-nav__login" href="/login">Login</a>
            @endguest

            @auth
                <div class="site-nav__dropdown" x-on:click.away="profileOpen = false">
                    <button type="button" class="site-nav__avatar" x-on:click="profileOpen = !profileOpen"
                            :aria-expanded="profileOpen.toString()" aria-controls="profile-menu">
                        <span class="sr-only">Open user menu</span>
                        <img src="{{ \Gravatar::get(auth()->user()->email) }}" alt="">
                    </button>

                    <div x-show="profileOpen" style="display:none;" class="site-nav__menu is-right" id="profile-menu">
                        <p class="site-nav__menu-label">{{ auth()->user()->name }}</p>
                        <x-nav-dropdown-link :link="route('user.profile', ['id' => Auth::id()])" title="Profile" />
                        <button type="submit" form="logout-form" class="site-nav__menu-link">{{ __('Sign out') }}</button>
                    </div>
                </div>
            @endauth

            <button x-on:click="mobileOpen = !mobileOpen" type="button" class="site-nav__toggle"
                    aria-controls="mobile-menu" :aria-expanded="mobileOpen.toString()">
                <span class="sr-only">Open main menu</span>
                <svg x-show="!mobileOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" style="display:none;" class="site-nav__mobile" id="mobile-menu">
        <x-mobile-nav-link :active="\Request::is('/')" link="/" title="Dashboard"/>
        <x-mobile-nav-link :active="\Request::is('browse') && !\Request::query('favorites')" link="/browse" title="Browse"/>
        @auth
            <x-mobile-nav-link :active="\Request::query('favorites')" :link="route('loadout.index', ['favorites' => 1])" title="Favorites" />
        @endauth
        <x-mobile-nav-link :active="\Request::is('pickrates/*')" :link="route('pickrates.classes')" title="Pick Rates"/>
        <x-mobile-nav-link :active="$isBuilding" link="/build" title="Build"/>
        <x-mobile-nav-link :active="\Request::is('blog') || \Request::is('blog/*')" link="/blog" title="Blog"/>
        @can('view-admin')
            <x-mobile-nav-link link="/admin" title="Admin"/>
        @endcan

        <div class="site-nav__mobile-account">
            @guest
                <x-mobile-nav-link link="/login" title="Login"/>
            @endguest
            @auth
                <div class="site-nav__mobile-user">
                    <img src="{{ \Gravatar::get(auth()->user()->email) }}" alt="">
                    <span>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </span>
                </div>
                <x-mobile-nav-link :link="route('user.profile', ['id' => Auth::id()])" title="Profile" />
                <button type="submit" form="logout-form" class="site-nav__mobile-link">{{ __('Sign out') }}</button>
            @endauth
        </div>
    </div>

    @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
            @csrf
        </form>
    @endauth
</nav>
