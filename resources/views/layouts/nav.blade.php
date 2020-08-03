<header class="lg:px-16 px-6 flex flex-wrap items-center lg:py-0 py-2 navMenu">

  <label for="menu-toggle" class="pointer-cursor mx-auto lg:hidden block"><svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><title>menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path></svg></label>
  <input class="hidden" type="checkbox" id="menu-toggle" />

  <div class="hidden lg:flex lg:items-center lg:w-auto w-full" id="menu">
    <nav>
      <ul class="lg:flex items-center justify-between text-base text-gray-700 pt-4 lg:pt-0">
        <svg class="{{ (\Request::is('/')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
            <a href="/">
            <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
            <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">HOME</text>
            </a>
        </svg>
        <svg class="{{ (\Request::is('browse')) || (\Request::is('preview/*')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
            <a href="/browse">
            <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
            <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">BROWSE</text>
            </a>
        </svg>
        <svg class="{{ (\Request::is('build')) ? 'navButtonActive' : 'navButton' }}" height="49" width="154">
            <a href="/build">
            <path d="m 2 2 l 134 0 l 14 14 l 0 30 l -149 0 l 0 -45" fill="transparent" stroke="#FC9E00" stroke-width="3"/>
            <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="34" font-family="BebasNeue">BUILD</text>
            </a>
        </svg>
      </ul>
      <div >
        @auth
        <div class="ml-4 flex items-center md:ml-6">

            <!-- Profile dropdown -->
            <div class="ml-3 relative">
                <div class="dropdown dropdown-overwrite">
                    <button class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid dropdown-toggle" type="button" id="user-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        <img class="h-8 w-8 rounded-full" src="{{ \Gravatar::get(auth()->user()->email) }}" alt="" />
                    </button>

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

  </div>

    </nav>
    
  </header>