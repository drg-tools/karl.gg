<div>
    <div class="previewHeaderBackground text-gray-300">
        <div class="flex justify-between items-center">
            @if ($loadout->creator)
                <h2 class="p-4">by <a class="text-karl-orange"
                        href="/profile/{{ $loadout->creator->id }}">{{ $loadout->creator->name }}</a> on
                    {{ \Carbon\Carbon::parse($loadout->updated_at)->format('d/m/Y') }}
                </h2>
            @else
                <h2 class="p-4">by <a class="text-karl-orange" href="/profile/0">Anonymous</a> on
                    {{ \Carbon\Carbon::parse($loadout->updated_at)->format('Y-m-d') }}
                </h2>
            @endif

            <div class="flex">
                @if (Auth::id() === optional($loadout->creator)->id)
                <a class="inline-flex items-center text-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto"
                    href="/build/{{ $loadout->id }}">
                        EDIT
                </a>
                @endif

                <!-- Trigger -->
                <share-button :loadout="{{ \Illuminate\Support\Js::from($loadout) }}" />

            </div>
        </div>

        <div class="previewHeaderContainer p-4">

            <div class="previewFooter mt-4">

                @auth
                    <bosco-salute :loadout-id="{{$loadout->id}}" :number-of-votes="{{ $loadout->getUpvotesCount($loadout->id) }}" />

                @endauth
                @guest
                <div class="bg-gray-900 font-bold sm:rounded text-center p-2 disabled">
                    <span>Salutes</span>
                    <img src="/assets/img/bosco.png" class="bosco-salute" />
                    <span class="salute-count">{{ $loadout->getUpvotesCount($loadout->id) }}</span>
                </div>
                @endguest

            </div>
            <div class="flex">
                @switch($loadout->character->id)
                    @case(1)
                        <img src="/assets/img/Engineer_portrait-128px.png" />
                    @break
                    @case(2)
                        <img src="/assets/img/Scout_portrait-128px.png" />
                    @break
                    @case(3)
                        <img src="/assets/img/Driller_portrait-128px.png" />
                    @break
                    @case(4)
                        <img src="/assets/img/Gunner_portrait-128px.png" />
                    @break

                @endswitch

            </div>
        </div>


    </div>
</div>
