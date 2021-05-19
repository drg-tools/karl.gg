<li class="flex flex-row flex-wrap">
    <div class="flex flex-col mb-4 w-3/5 md:w-3/4">
        <a href="{{ route('preview.show', $loadout->id) }}" class="block bg-gray-700 hover:bg-gray-800">
            <div class="px-4 py-4 sm:px-6 border-l-4 border-{{ Str::lower($loadout->character->name) }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-white truncate">
                        {{ Str::limit($loadout->name, 30) }}
                    </p>
                    <div class="ml-2 flex-shrink-0 flex">
                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-300 text-gray-900">
                            {{ $loadout->votes_count }}
                        </p>
                    </div>
                </div>
                <div class="mt-2 sm:flex sm:justify-between">
                    <div class="sm:flex">
                        <p class="flex items-center text-sm text-gray-300">
                            {{ $loadout->creator->name ?? 'Anonymous' }}
                        </p>
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                        @if($weapons(0))
                            <img
                                class="w-10 filter invert"
                                src="/assets/{{ $weapons(0)->image }}.svg" alt="{{ $weapons(0)->name }} icon"/>
                        @endif
                        @if($weapons(1))
                            <img
                                class="w-10 ml-2 filter invert"
                                src="/assets/{{ $weapons(1)->image }}.svg" alt="{{ $weapons(1)->name }} icon"/>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="flex flex-col">
        <a class="px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded" href="/build/{{$loadout->id}}">
                @guest
                    COPY
                @endguest
                @auth
                    @if($loadout->creator->id == auth()->user()->id)
                    EDIT
                    @else 
                    COPY
                    @endif
                @endauth                
        </a>
        @auth
        @if($loadout->creator->id == auth()->user()->id)
            <form method="POST" action="/build/delete/{{$loadout->id}}">
                @method('DELETE')
                @csrf
                <button class="mt-4 px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded" type="submit">
                    DELETE
                </button>
            </form>
            
            @endif
        @endauth
        
    </div>
</li>
