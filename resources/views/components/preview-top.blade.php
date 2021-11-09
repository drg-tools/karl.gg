<div>
    <div class="previewHeaderBackground text-gray-300">
        <div class="flex justify-between items-center">
            @if($loadout->creator)
            <h2 class="p-4">by <a class="text-karl-orange" href="/profile/" . {{$loadout->creator->id}}>{{$loadout->creator->name}}</a> on
                {{ \Carbon\Carbon::parse($updatedAt)->format('d/m/Y')}}
            </h2>
            @else
            <h2 class="p-4">by <a class="text-karl-orange" href="/profile/0">Anonymous</a> on
                {{ \Carbon\Carbon::parse($updatedAt)->format('Y-m-d')}}
            </h2>
            @endif
            
            <div class="flex">
                <a class="inline-flex items-center text-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto"
                    href="/build/{{$loadout->id}}" >
                    @auth
                        @if(Auth::user()->id === $loadout->creator->id)
                            EDIT
                        @endif
                    @endauth
                    @guest
                        COPY
                    @endguest
                </a>
                
                <!-- Trigger -->
                <button class="clip-btn inline-flex items-center text-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto" data-clipboard-text="{{$pageUrl}}">
                    SHARE
                </button>
                
            </div>
            <!-- todo: use texts from old karl and style toast messages -->
        </div>
        {{-- <div class="previewHeaderContainer p-4" :class="getHeaderImageClass">
            <div class="previewFooter mt-4">
                <!-- todo: tooltip on salutes container! -->
                <div v-on:click="onToggleVote" class="bg-gray-900 font-bold sm:rounded text-center p-2 cursor-pointer hover:bg-gray-800">
                    <span>Salutes</span>
                    <img src="/assets/img/bosco.png" :class="getUserVotedState" class="bosco-salute"/>
                    <span class="salute-count">{{ loadoutDetails.votes }}</span>
                </div>
            </div>
        </div> --}}

       
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