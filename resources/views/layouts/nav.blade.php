<nav class="navMenu">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline">
                        <div>
                            <svg class="{{ (\Request::is('/')) ? 'navButtonActive' : 'navButton' }}" height="49"
                                 width="154">
                                <a href="/">
                                    <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent"
                                          stroke="#FC9E00" stroke-width="3"/>
                                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34"
				    >HOME
                                    </text>
                                </a>
                            </svg>
                        </div>
                        <div>
                            <svg
                                class="{{ (\Request::is('browse')) || (\Request::is('preview/*')) ? 'navButtonActive' : 'navButton' }}"
                                height="49" width="154">
                                <a href="/browse">
                                    <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent"
                                          stroke="#FC9E00" stroke-width="3"/>
                                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34"
				    >BROWSE
                                    </text>
                                </a>
                            </svg>
                        </div>
                        <div>
                            <svg class="{{ (\Request::is('build')) ? 'navButtonActive' : 'navButton' }}" height="49"
                                 width="154">
                                <a href="/build">
                                    <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent"
                                          stroke="#FC9E00" stroke-width="3"/>
                                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34"
				    >BUILD
                                    </text>
                                </a>
                            </svg>
                        </div>
                    </div>
                </div>
                @hasrole('super-admin')
                <svg class="navButton" height="49" width="154">
                    <a href="/admin">
                        <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent"
                              stroke="#FC9E00" stroke-width="3"/>
                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34"
			>ADMIN
                        </text>
                    </a>
                </svg>
                @endhasrole
            </div>
            <div>
                <div class="hidden md:block ml-4 flex items-center md:ml-6">
		@auth
                    <!-- Profile dropdown -->
			<div class="ml-3 relative">
			    <div class="dropdown dropdown-overwrite">
				<button
				    class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid dropdown-toggle"
				    type="button" id="user-menu" data-toggle="dropdown" aria-haspopup="true"
				    aria-expanded="false">
				    <img class="h-8 w-8 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}"
					 alt=""/>
				</button>

				<div
				    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 rounded-md bg-white shadow-xs dropdown-menu"
				    aria-labelledby="user-menu">
				    <a href="{{ route('user.profile', ['id' => Auth::id()]) }}"
				       class="block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100 dropdown-item"
				       role="menuitem">Your Profile</a>
				    @hasrole('super-admin')
				    <a href="{{ route('settings.tokens') }}"
				       class="block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100 dropdown-item"
				       role="menuitem">Settings</a>
				    @endhasrole
				    <a class="block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100 dropdown-item"
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
                        </div>
                    @endauth
                    @guest
                        <div>
                            <svg class="{{ (\Request::is('login')) ? 'navButtonActive' : 'navButton' }}" height="49"
                                 width="154">
                                <a href="/login">
                                    <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent"
                                          stroke="#FC9E00" stroke-width="3"/>
                                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle"
                                          font-size="34">LOGIN
                                    </text>
                                </a>
                            </svg>
                        </div>
                    @endguest
                </div>
                <div class="-mr-2 flex md:hidden text-white">
		    <!-- Mobile menu button -->
		    <button
			id="mobile-menu-button"
			class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
			<!-- Menu open: "hidden", Menu closed: "block" -->
			<svg class="block h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
			    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
				  d="M4 6h16M4 12h16M4 18h16"/>
			</svg>
			<!-- Menu open: "block", Menu closed: "hidden" -->
			<svg class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
			    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
				  d="M6 18L18 6M6 6l12 12"/>
			</svg>
		    </button>
		</div>
            </div>
        </div>
    </div>
    <!--
          Mobile menu, toggle classes based on menu state.

          Open: "block", closed: "hidden"
        -->
    <div class="hidden md:hidden block" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 sm:px-3">
	    <a href="/"
	       class="{{ \Request::is('/') ? 'text-white bg-gray-900' : 'hover:bg-gray-700 hover:text-white text-gray-300' }} block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Home</a>
	    <a href="/browse"
	       class="{{  \Request::is('browse') ? 'text-white bg-gray-900' : 'hover:bg-gray-700 hover:text-white text-gray-300' }} mt-1 block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Browse</a>
	    <a href="/build"
	       class="{{ \Request::is('build')  || \Request::is('preview/*') ? 'text-white bg-gray-900' : 'hover:bg-gray-700 hover:text-white text-gray-300' }} mt-1 block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Build</a>
            @hasrole('super-admin')
	    <a href="/admin"
	       class="{{ \Request::is('admin') ? 'text-white bg-gray-900' : 'hover:bg-gray-700 hover:text-white text-gray-300' }} mt-1 block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Admin</a>
            @endhasrole
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            @auth
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <div class="dropdown dropdown-overwrite">
			    <button
                                class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid dropdown-toggle"
                                type="button" id="user-mobile-menu" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <img class="h-8 w-8 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}"
                                     alt=""/>
                            </button>
			    {{-- <img class="h-10 w-10 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}" --}}
			    {{-- alt=""/> --}}

                            <div
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 rounded-md bg-white shadow-xs dropdown-menu"
                                aria-labelledby="user-mobile-menu">
                                <a href="{{ route('user.profile', ['id' => Auth::id()]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item"
                                   role="menuitem">Your Profile</a>
                                @hasrole('super-admin')
                                <a href="{{ route('settings.tokens') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item"
                                   role="menuitem">Settings</a>
                                @endhasrole
                                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dropdown-item"
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
                    </div>
                </div>
            @endauth
            @guest
                <a href="/login"
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Login</a>
            @endguest
        </div>
    </div>
</nav>
