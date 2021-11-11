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
                <a class="inline-flex items-center text-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto"
                    href="/build/{{ $loadout->id }}">
                    @auth
                        @if (Auth::user()->id === optional($loadout->creator)->id)
                            EDIT
                        @else
                            COPY
                        @endif
                            
                    @endauth
                    @guest
                        COPY
                    @endguest
                </a>

                <!-- Trigger -->
                <share-button :page-url="{{ json_encode(Request::url()) }}" />

            </div>
            <!-- todo: use texts from old karl and style toast messages -->
        </div>

        {{-- todo: add class image --}}
        <div class="previewHeaderContainer p-4">

            <div class="previewFooter mt-4">
                <!-- todo: tooltip on salutes container! -->
                {{-- todo: bosco onclick --}}
                @auth
                    <bosco-salute :loadout-id="{{$loadout->id}}" :number-of-votes="{{ $loadout->getUpvotesCount($loadout->id) }}" />
                    
                @endauth
                @guest
                <div class="bg-gray-900 font-bold sm:rounded text-center p-2 disabled">
                    <span>Salutes</span>
                    {{-- todo: voted states --}}
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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script type="text/javascript">
        var clipboard = new ClipboardJS('.clip-btn');

        clipboard.on('success', function(e) {
            // we should show a quick alert here
            console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);

            e.clearSelection();
        });
    </script>
@endsection
