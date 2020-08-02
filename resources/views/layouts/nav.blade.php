<nav class="navMenu">
<!-- todo: mobile nav -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
{{--                    <img class="h-8 w-8" src="/img/logos/workflow-mark-on-dark.svg" alt="Workflow logo" />--}}
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline">
{{--                        <a href="/" class="px-3 py-2 rounded-md text-sm font-medium text-white bg-gray-900 focus:outline-none focus:text-white focus:bg-gray-700">Home</a>--}}
                        <div>
                            <svg class="{{ (\Request::is('/')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
                                <a href="/">
                                <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
                                <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">HOME</text>
                                </a>
                            </svg>
                        </div>
                        <div>
                            <svg class="{{ (\Request::is('browse')) || (\Request::is('preview/*')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
                                <a href="/browse">
                                <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
                                <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">BROWSE</text>
                                </a>
                            </svg>
                        </div>
                        <div>
                        <!-- todo: how can i have class active if url is build/xyz? -->
                            <svg class="{{ (\Request::is('build')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
                                <a href="/build">
                                <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
                                <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">BUILD</text>
                                </a>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                @auth
                <div class="ml-4 flex items-center md:ml-6">

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div class="dropdown dropdown-overwrite">
                            <button class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid dropdown-toggle" type="button" id="user-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                <img class="h-8 w-8 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}" alt="" />
                            </button>


                                {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user-menu">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div> --}}
                        <!--
                          Profile dropdown panel, show/hide based on dropdown state.

                          Entering: "transition ease-out duration-100"
                            From: "transform opacity-0 scale-95"
                            To: "transform opacity-100 scale-100"
                          Leaving: "transition ease-in duration-75"
                            From: "transform opacity-100 scale-100"
                            To: "transform opacity-0 scale-95"
                        -->
                       <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 rounded-md bg-white shadow-xs dropdown-menu" aria-labelledby="user-menu">
                                <a href="{{ route('user.profile', ['id' => Auth::id()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item" role="menuitem">Your Profile</a>
                                @hasrole('super-admin')
                                    <a href="{{ route('settings.tokens') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item" role="menuitem">Settings</a>
                                @endhasrole
                                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item" href="{{ route('logout') }}"  role="menuitem"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Sign out') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                        </div>
                    </div>
                </div>
                @endauth
                @guest
                <div>
                    <svg class="{{ (\Request::is('login')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
                        <a href="/login">
                        <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">LOGIN</text>
                        </a>
                    </svg>
                    </div>
                @endguest
                @hasrole('super-admin')
                <svg class="navButton" height="49" width="154">
                    <a href="/admin">
                    <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">ADMIN</text>
                    </a>
                </svg>
                @endhasrole
            </div>
            <div class="-mr-2 flex md:hidden" id="mobile-menu-button">
                <!-- Mobile menu button -->
                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <!-- Menu open: "hidden", Menu closed: "block" -->
                    <svg class="block h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Menu open: "block", Menu closed: "hidden" -->
                    <svg class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.

      Open: "block", closed: "hidden"
    -->
    <div class="hidden md:hidden block" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 sm:px-3">
            <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-gray-900 focus:outline-none focus:text-white focus:bg-gray-700">Home</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            @auth
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}" alt="" />
                </div>
{{--                <div class="ml-3">--}}
{{--                    <div class="text-base font-medium leading-none text-white">Tom Cook</div>--}}
{{--                    <div class="mt-1 text-sm font-medium leading-none text-gray-400">tom@example.com</div>--}}
{{--                </div>--}}
            </div>
{{--            <div class="mt-3 px-2">--}}
{{--                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Your Profile</a>--}}
{{--                <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Settings</a>--}}
{{--                <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Sign out</a>--}}
{{--            </div>--}}
            @endauth
            @guest
                <a href="/login" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Login</a>
            @endguest
        </div>
    </div>
</nav>
