    <h1 class="uppercase text-center text-xl">{{$title}}</h1>
    <div class="cardGroups flex flex-wrap mb-4 ">
        @foreach($loadoutList as $loadout)
        <div class="loadoutCards w-full px-1">
            <x-dashboard-loadout :loadout="$loadout"/>
        </div>
        @endforeach
    </div>
