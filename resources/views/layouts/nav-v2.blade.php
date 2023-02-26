<nav class="bg-gray-700" x-data="{ mobileOpen: false, profileOpen: false, openBuild: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="/">
                        <img class="h-8 w-8" src="{{ asset('assets/img/k-logo.svg') }}"
                             alt="{{ config('app.name') }}">
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-nav-link :active="\Request::is('/')" link="/" title="Dashboard"/>


                        <div class="relative inline-block text-left" x-on:click="openBuild = !openBuild">
                            <x-nav-dropdown title="Loadouts" />

                            <div
                                x-show.transition="openBuild"
                                x-on:click.away="openBuild = false"
                                style="display:none;"
                                class="origin-top-right absolute z-50 right-0 mt-2 w-48 rounded-md shadow-lg py-1 text-gray-300 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            >
                                <x-nav-dropdown-link :link="route('loadout.index')" :active="\Request::is('browse') && !\Request::query('favorites')" title="Browse" />
                                @auth
                                <x-nav-dropdown-link :link="route('loadout.index', ['favorites' => 1])" :active="\Request::query('favorites')" title="Favorites" />
                                @endauth
                                <x-nav-dropdown-link :link="route('pickrates.classes')" :active="\Request::is('pickrates/*')" title="Pick Rates" />
                            </div>
                        </div>

                        <x-nav-link :active="\Request::is('build')" link="/build" title="Build"/>
                        <x-nav-link :active="\Request::is('blog') || \Request::is('blog/*')" link="/blog" title="Blog"/>

                        @can('view-admin')
                        <x-nav-link link="/admin" title="Admin"/>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    @guest
                    <x-nav-link link="/login" title="Login"/>
                    @endguest

                    <!-- Profile dropdown -->
                    @auth
                    <div class="ml-3 relative">
                        <div x-on:click="profileOpen = true">
                            <button type="button"
                                    class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full"
                                     src="{{ \Gravatar::get(auth()->user()->email) }}"
                                     alt="{{ auth()->user()->email }}">
                            </button>
                        </div>

                        <!--
                          Dropdown menu, show/hide based on menu state.

                          Entering: "transition ease-out duration-100"
                            From: "transform opacity-0 scale-95"
                            To: "transform opacity-100 scale-100"
                          Leaving: "transition ease-in duration-75"
                            From: "transform opacity-100 scale-100"
                            To: "transform opacity-0 scale-95"
                        -->
                        <div
                            x-show.transition="profileOpen"
                            x-on:click.away="profileOpen = false"
                            style="display:none;"
                            class="origin-top-right z-50 absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                        >
                            <x-nav-dropdown-link :link="route('user.profile', ['id' => Auth::id()])" title="Profile" />

                            @can('access-api')
                            <x-nav-dropdown-link :link="route('settings.tokens')" title="Settings" />
                            @endcan

                            <a class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700"
                               href="{{ route('logout') }}" role="menuitem"
                               onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                {{ __('Sign out') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>

                    </div>
                    @endauth
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button x-on:click="mobileOpen = true"
                        type="button"
                        class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!--
                      Heroicon name: outline/menu

                      Menu open: "hidden", Menu closed: "block"
                    -->
                    <svg
                        x-show="!mobileOpen"
                        style="display:none;"
                        class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <!--
                      Heroicon name: outline/x

                      Menu open: "block", Menu closed: "hidden"
                    -->
                    <svg x-show="mobileOpen"
                         style="display:none;"
                         class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div
        x-show.transition="mobileOpen"
        x-on:click.away="mobileOpen = false"
        style="display:none;"
        class="md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <x-mobile-nav-link :active="\Request::is('/')" link="/" title="Dashboard"/>
            <x-mobile-nav-link :active="\Request::is('browse')" link="/browse" title="Browse"/>
            <x-mobile-nav-link :active="\Request::is('pickrates/*')" :link="route('pickrates.classes')" title="Pick Rates"/>
            <x-mobile-nav-link :active="\Request::is('build')" link="/build" title="Build"/>
            @auth
            <x-mobile-nav-link :link="route('loadout.index', ['favorites' => 1])" title="Favorites" />
            @endauth
            <x-mobile-nav-link :active="\Request::is('blog') || \Request::is('blog/*')" link="/blog" title="Blog"/>

            @can('view-admin')
            <x-mobile-nav-link link="/admin" title="Admin"/>
            @endcan

        </div>

        <div class="pt-4 pb-3 border-t border-gray-700">
            @auth
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full"
                         src="{{ \Gravatar::get(auth()->user()->email) }}"
                         alt="{{ auth()->user()->email }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                    <div class="text-sm font-medium text-gray-400">{{ auth()->user()->email }}</div>
                </div>
{{--                <button--}}
{{--                    class="ml-auto bg-gray-800 flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">--}}
{{--                    <span class="sr-only">View notifications</span>--}}
{{--                    <!-- Heroicon name: outline/bell -->--}}
{{--                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                         stroke="currentColor" aria-hidden="true">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}
            </div>
            @endauth

            <div class="mt-3 px-2 space-y-1">
                @guest
                    <x-mobile-nav-link link="/login" title="Login"/>
                @endguest
                @auth
                    <x-mobile-nav-link :link="route('user.profile', ['id' => Auth::id()])" title="Profile" />

                    @can('access-api')
                    <x-mobile-nav-link :link="route('settings.tokens')" title="Settings" />
                    @endcan

                    <a class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium"
                       href="{{ route('logout') }}" role="menuitem"
                       onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Sign out') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>
