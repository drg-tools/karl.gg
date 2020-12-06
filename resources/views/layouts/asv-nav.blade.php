<nav class="navMenu w-full">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="hidden md:block">
                    <div class="flex items-baseline">
                        <h1 class="asvItemTitle">{{ $gun[0]->name }}</h2>
                        <h3 class="asvItemType">
                            @if($gun[0]->character_slot == 1)
                            Primary Weapon
                            @elseif($gun[0]->character_slot == 2)
                            Secondary Weapon
                            @else 
                            Equipment
                            @endif
                        </h3>
                        <h3 class="combo-main">
                            {{$combo}}
                        @if($modMatrix['selected_index'][6]['selected'])
                            / {{$overclock[0]->overclock_name}}
                        @endif
                        </h3>


                    </div>  
                </div>
            </div>
            <div>
                <a class="button px-5" href="/">
                    <span class="buttonText">BACK TO KARL</span>
                </a>
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
    
</nav>
